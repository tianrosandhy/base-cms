<?php
namespace Module\Main\Http\Skeleton;

use Module\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class LogSkeleton extends DataTable
{

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('log');

		$this->structure[] = DataStructure::field('created_at')
			->name('Created At')
			->inputType('date');
		$this->structure[] = DataStructure::field('url')
			->name('URL');
		$this->structure[] = DataStructure::field('label')
			->name('Label');
		$this->structure[] = DataStructure::field('user_id')
			->name('User');
		$this->structure[] = DataStructure::field('data')
			->name('Data');
		
	}



	
	//MANAGE OUTPUT DATATABLE FORMAT PER BARIS
	public function tableFormat(){
		$out = [];
		
		foreach($this->raw_data as $row){
			$data = json_decode($row->data, true);
			$dt = '<div style="overflow-x:scroll; width:400px;"><table style="font-size:80%">';
			if(!is_array($data)){
				$dt.= '<tr><td>'.$data.'</td></tr>';
			}
			else{
				foreach($data as $par => $val){
					
					$dt .= '
					<tr>
						<td style="padding:0"><strong>'.$par.'</strong></td>
						<td style="padding:0; padding-left:.5em">';
						if(!is_array($val)){
							$dt.= json_encode($val);
						}
						else{
							$dt .= '<table>';
							foreach($val as $subpar => $subval){
								$dt .= '<tr><td style="padding:0"><strong><em>'.$subpar.'</em></strong></td>';
								$dt .= '<td style="padding:0; padding-left:.5em;">'.json_encode($subval).'</td></tr>';
							}
							$dt.= '</table>';
						}
					$dt .= '</td>
					</tr>
					';
				}
			}

			$dt .= '</table></div>';


			$out[] = [
				'url' => $row->url,
				'label' => $row->label,
				'user_id' => isset($row->getUser->name) ? $row->getUser->name : '-',
				'data' => $dt,
				'created_at' => date('d F Y H:i:s', strtotime($row->created_at)),
				'action' => self::deleteButton($row)
			];
		}

		$this->output = $out;
	}

	protected function deleteButton($row){
		if(has_access('admin.log.delete')){
			return $this->actionButton(
				'Delete', 
				url()->route('admin.log.delete', ['id' => $row->id]), 
				[
					'class' => ['btn', 'btn-sm', 'btn-danger delete-button'],
					'data-id' => $row->id
				]
			);
		}
	}



}