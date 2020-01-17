<?php
namespace Module\Main\Http\Repository;

use Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class AnalyticRepository
{
	public $report;

	public function __construct(){
		if(env('ANALYTICS_VIEW_ID')){
			//report will not be generated if it catch any exception from Google API Client
			try{
				$this->generateWeeklyReport();
			}catch(\Exception $e){}
		}
	}


	protected function generateWeeklyReport(){
		//weekly report
		$start_date = Carbon::today()->subDays(6)->startOfDay();
		$end_date = Carbon::today();
		$this->weekly = Analytics::fetchTotalVisitorsAndPageViews(Period::create($start_date, $end_date));

		$start_date = Carbon::today()->subDays(13)->startOfDay();
		$end_date = Carbon::today()->subDays(7)->startOfDay();
		$this->weekly_compare = Analytics::fetchTotalVisitorsAndPageViews(Period::create($start_date, $end_date));

		foreach($this->weekly as $row){
			$weekly['label'][] = $row['date']->format('l');
			$weekly['this_week']['visitor'][] = $row['visitors'];
			$weekly['this_week']['pageview'][] = $row['pageViews'];
		}
		
		foreach($this->weekly_compare as $row){
			$weekly['last_week']['visitor'][] = $row['visitors'];
			$weekly['last_week']['pageview'][] = $row['pageViews'];
		}
		$this->report['weekly'] = $weekly;
	}

}