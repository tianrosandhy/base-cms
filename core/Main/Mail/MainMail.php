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
        $banner_image,
        $title,
        $subtitle,
        $content,
        $additional_content,
        $button,
        $footer_text,
        $file,
        $theme,
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

    public function mailViewPath(){
        return 'main::mail.theme1.master';
    }

    public function setVar($additional=[]){
    	$this->var = array_merge([
            'body_background' => '#DDDBDB',
            'wrapper_background' => '#fafafa',
            'primary_color' => '#0F75BB',
            'text_color_light' => '#fafafa',
            'text_color_dark' => '#333333',
            'max_width' => 600
		], $additional);
		return $this;
    }


    public function build(){
        if(empty($this->var)){
        	$this->setVar();
        }
        $this->theme = $this->var;

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
            ->view($this->mailViewPath())
            ->with($objvar);

        if(!empty($this->rep)){
            $output = $output->replyTo($this->rep);
        }

        if(!empty($this->file)){
            if(is_array($this->file)){
                foreach($this->file as $file){
                    $output = $output->attach($file);
                }
            }
            else{
                $output = $output->attach($this->file);
            }
        }

        return $output;
    }

}