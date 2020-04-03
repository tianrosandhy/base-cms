<?php
namespace Core\Main\Http\Repository;

use Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class AnalyticRepository
{
	public 
		$report,
		$request;

	public function __construct($request=[]){
		$this->request = $request;

		if(env('ANALYTICS_VIEW_ID')){
			$this->current_date = Carbon::today();
			$this->previous_week = Carbon::today()->subDays(6)->startOfDay();
			$this->previous_start_week = Carbon::today()->subDays(13)->startOfDay();
			$this->previous_end_week = Carbon::today()->subDays(7)->startOfDay();

			$this->first_day_month = new Carbon('first day of this month');
			$this->last_day_month = new Carbon('last day of this month');

			$this->max_result = isset($this->request['max_result']) ? intval($this->request['max_result']) : 10;
			if($this->max_result > 50){
				$this->max_result = 50;
			}
			if($this->max_result < 5){
				$this->max_result = 5;
			}


			//in case permintaan report tanpa date query
			if(!isset($this->request['period'][0]) || !isset($this->request['period'][1])){
				//report will not be generated if it catch any exception from Google API Client
				try{
					$this->generateWeeklyReport();
					$this->generateRangedReport();
					$this->generateMostVisitedReport();
					$this->generateTopLandingReport();
					$this->generateSessionDurationReport();
					$this->generateDeviceReport();
				}catch(\Exception $e){}
			}
			//permintaan report dengan filter tanggal
			else{
				$this->selected_first_date = new Carbon($this->request['period'][0]);
				$this->selected_end_date = new Carbon($this->request['period'][1]);

				//report will not be generated if it catch any exception from Google API Client
//				try{
					$this->generateRangedReport($this->selected_first_date, $this->selected_end_date);
					$this->generateMostVisitedReport($this->selected_first_date, $this->selected_end_date);
					$this->generateTopLandingReport($this->selected_first_date, $this->selected_end_date);
					$this->generateSessionDurationReport($this->selected_first_date, $this->selected_end_date);
					$this->generateDeviceReport($this->selected_first_date, $this->selected_end_date);
//				}catch(\Exception $e){};
			}
		}
	}


	protected function generateWeeklyReport(){
		//weekly report
		$weekly_data = $this->visitorAndPageViewsByPeriod($this->previous_week, $this->current_date);
		$weekly_compare_data = $this->visitorAndPageViewsByPeriod($this->previous_start_week, $this->previous_end_week);

		foreach($weekly_data as $row){
			$weekly['label'][] = $row['date']->format('l');
			$weekly['this_week']['visitor'][] = $row['visitors'];
			$weekly['this_week']['pageview'][] = $row['pageViews'];
		}
		
		foreach($weekly_compare_data as $row){
			$weekly['last_week']['visitor'][] = $row['visitors'];
			$weekly['last_week']['pageview'][] = $row['pageViews'];
		}
		$this->report['weekly'] = $weekly;
	}

	protected function generateRangedReport($first=null, $end=null){
		$as_monthly = true;
		if(!empty($first) && !empty($end)){
			$as_monthly = false;
		}
		else{
			$first = $this->first_day_month;
			$end = $this->last_day_month;
		}

		$groupdata = $this->visitorAndPageViewsByPeriod($first, $end);
		foreach($groupdata as $row){
			$gdata['label'][] = $row['date']->format('d M');
			$gdata['visitor'][] = $row['visitors'];
			$gdata['pageview'][] = $row['pageViews'];
		}
		if($as_monthly){
			$this->report['monthly'] = $gdata;
		}
		else{
			$this->report['ranged'] = $gdata;
		}
	}


	protected function generateMostVisitedReport($first_date=null, $last_date=null){
		$this->report['most_visited'] = Analytics::fetchMostVisitedPages($this->createPeriod($first_date, $last_date), $this->max_result);
	}

	protected function generateTopLandingReport($first_date=null, $last_date=null){
		$response = Analytics::performQuery($this->createPeriod($first_date, $last_date), 'ga:entrances,ga:bounces', [
			'dimensions' => 'ga:landingPagePath,ga:pageTitle',
			'sort' => '-ga:entrances',
			'max-results' => $this->max_result
		]);

		$this->report['top_landing'] = collect($response['rows'] ?? [])->map(function (array $pageRow) {
            return [
                'url' => $pageRow[0],
                'title' => $pageRow[1],
                'entrances' => (int) $pageRow[2],
                'bounces' => (int) $pageRow[3],
                'bounce_rate' => ($pageRow[2] > 0 ? ($pageRow[3] / $pageRow[2] * 100) : 0),
            ];
        });
	}

	protected function generateSessionDurationReport($first_date=null, $end_date=null){
		$response = Analytics::performQuery($this->createPeriod($first_date, $end_date), 'ga:sessionDuration,ga:sessions', [
			'dimensions' => 'ga:pagePath,ga:pageTitle',
			'sort' => '-ga:sessionDuration',
			'max-results' => $this->max_result
		]);

		$this->report['session_duration'] = collect($response['rows'] ?? [])->map(function(array $pageRow){
			return [
				'url' => $pageRow[0],
				'title' => $pageRow[1],
				'session_duration' => $this->toTimeString($pageRow[2]),
				'session_count' => intval($pageRow[3]),
				'avg_session_duration' => $this->toTimeString($pageRow[2] / $pageRow[3])
			];
		});
	}

	protected function generateDeviceReport($first_date=null, $end_date=null){
		$response = Analytics::performQuery($this->createPeriod($first_date, $end_date), 'ga:users', [
			'dimensions' => 'ga:browser,ga:operatingSystem',
			'sort' => '-ga:users',
			'max-results' => $this->max_result
		]);

		$this->report['device'] = collect($response['rows'] ?? [])->map(function(array $pageRow){
			return [
				'browser' => $pageRow[0],
				'os' => $pageRow[1],
				'count' => $pageRow[2]
			];
		});
	}





	protected function visitorAndPageViewsByPeriod(Carbon $start_date, Carbon $end_date){
		return Analytics::fetchTotalVisitorsAndPageViews($this->createPeriod($start_date, $end_date));
	}

	protected function createPeriod($start_date=null, $end_date=null){
		if(empty($start_date)){
			$start_date = $this->first_day_month;
		}
		if(empty($end_date)){
			$end_date = $this->last_day_month;
		}

		return Period::create($start_date, $end_date);
	}

	protected function toTimeString($duration=null){
		$duration = intval($duration);

		$hour = floor($duration / 60 / 60);
		$minute = floor(($duration - ($hour * 60 * 60)) / 60);
		$second = floor(($duration - ($hour * 60 * 60) - ($minute * 60)));

		if($minute <= 9){
			$minute = '0'.$minute;
		}
		if($second <= 9){
			$second = '0'.$second;
		}

		if(intval($hour) > 0){
			return intval($hour).':'.$minute.':'.$second;
		}
		else{
			return $minute.':'.$second;
		}
	}

}