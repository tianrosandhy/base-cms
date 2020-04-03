<?php
namespace Core\Main\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class MainMail extends Mailable
{
    use Queueable, SerializesModels;

    public 
        $subject,
        $precontent,
        $title,
        $subheader,
        $top_description,
        $content,
        $button,
        $unsubscribe_url,
        $additional_footer,
        $additional_view,
        $file,
        $var,
        $rep;

    public function __call($name, $arguments){
    	$method = substr($name, 0, 3);
    	if(in_array($method, ['get', 'set'])){
    		$prop = substr($name, 3);
    		$prop = Str::snake($prop);

    		if(property_exists($this, $prop)){
		    	if($method == 'get'){
		    		return $this->{$prop};
		    	}
		    	else if($method == 'set' && isset($arguments[0])){
		    		$this->{$prop} = $arguments[0];
		    		return $this;
		    	}
    		}
    	}
    }

    public function setVar($additional=[]){
    	$this->var = array_merge([
			'max-width' => 500,
			'background-color' => '#f0f0f0',
			'main-top-color' => '#0089d1',
			'button-color' => '#37b349',
			'button-text-color' => '#fff',
			'logo' => asset('styling/src/img/logo.png'),
			'logo-height' => 50,
			'content-align' => 'center',
		], $additional);
		return $this;
    }


    public function build(){
        if(empty($this->var)){
        	$this->setVar();
        }

        $objvar = get_object_vars($this);
        $exclude  = [
        	'html', 'view', 'textView', 'viewData', 'callbacks', 'connection', 'queue', 'chainConnection', 'chainQueue', 'delay', 'chained'
        ];
        foreach($exclude as $exc){
        	if(isset($objvar[$exc])){
        		unset($objvar[$exc]);
        	}
        }

        $output = $this
            ->subject($this->subject)
            ->view('main::mail.master')
            ->with($objvar);

        if(!empty($this->rep)){
            $output = $output->replyTo($this->rep);
        }

        if(!empty($this->file)){
            foreach($this->file as $file){
                $output = $output->attach($file);
            }
        }

        return $output;
    }

}