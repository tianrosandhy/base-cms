<?php
namespace Core\Main\Http\Traits;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait HandleRecaptcha
{
	public function handleRecaptcha(){
		$post = $this->request->all();
		if(!isset($post['g-recaptcha-response'])){
			return false;
		}

		try{
			$client = new Client([
				'base_uri' => 'https://www.google.com/recaptcha/'
			]);
			$response = $client->request('POST', 'api/siteverify?secret='.env('RECAPTCHA_SECRET_KEY').'&response='.$post['g-recaptcha-response']);
			$output = $response->getBody()->getContents();
			$json = json_decode($output, true);
			if($json['success'] == true){
				return true;
			}
			return false;
		}
		catch(\Exception $e){
			//log exception 
			Log::info('GOOGLE RECAPTCHA ERROR : ' . json_encode($e->getMessage()));
			return false;
		}
	}
}