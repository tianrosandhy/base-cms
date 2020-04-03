<?php
namespace Module\Contact\Services;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Services\BaseInstance;
use Module\Contact\Exceptions\ContactException;
use Validator;
use Illuminate\Validation\ValidationException;
use Mail;
use Core\Main\Mail\MainMail;
use Core\Main\Http\Traits\HandleRecaptcha;

class ContactInstance extends BaseInstance
{
	use HandleRecaptcha;

	public function __construct(){
		parent::__construct('contact');
	}

	public function store(){
		$this->validateContactRequest();
		$is_spam = $this->checkIfRequestSpam();
		$instance = $this->createContactInstance($is_spam);

		if(!$is_spam){
			$is_sent = $this->sendNotificationToAdmin($instance);
		}

		return true;
	}


	public function sendNotificationToAdmin($contact_instance){
		$mail = new MainMail();
		$mail->setSubject($contact_instance->subject);
		$mail->setTitle($contact_instance->subject);
		$mail->setContent(
			"
			Hi, Administrator. You just received new message with details below : <br><br>
			Sender : ".strip_tags($contact_instance->full_name)."<br>
			Email : ".strip_tags($contact_instance->email)."<br>
			Phone : ".($contact_instance->phone ? $contact_instance->phone : '-')."<br>
			<br>
			".strip_tags($contact_instance->message)."
			"
		);
		$mail->setRep($contact_instance->email);

		try {
			Mail::to(setting('site.mail_receiver', 'tianrosandhy@gmail.com'))->send($mail);
			return 1;
		} catch (\Exception $e) {
			return 0;
		}
	}


	protected function validateContactRequest(){
		$validator = Validator::make($this->request->all(), [
			'full_name' => 'required',
			'email' => 'required|email|strict_mail',
			'message' => 'required'
		]);

		if($validator->fails()){
			return $validator->validate();
		}

		if(env('RECAPTCHA_SITE_KEY') && env('RECAPTCHA_SECRET_KEY')){
			if(!$this->handleRecaptcha()){
				throw ValidationException::withMessages([
					'full_name' => 'Please recheck the captcha'
				]);
			}
		}
	}

	protected function checkIfRequestSpam(){
		$spam = false;
		if(strlen(strip_tags($this->request->full_name)) <> strlen($this->request->full_name)){
			$spam = true;
		}
		if(strlen(strip_tags($this->request->message)) <> strlen($this->request->message)){
			$spam = true;
		}
		return $spam;
	}

	protected function createContactInstance($is_spam){
		return (new CrudRepository('contact'))->insert([
			'full_name' => $this->request->full_name,
			'email' => $this->request->email,
			'phone' => $this->request->phone,
			'subject' => 'New Message from ' . strip_tags(htmlentities($this->request->full_name)),
			'message' => $this->request->message,
			'is_spam' => $is_spam,
			'is_active' => 0
		]);
	}

}