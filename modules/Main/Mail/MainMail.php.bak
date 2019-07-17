<?php
namespace Module\Main\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MainMail extends Mailable
{
    use Queueable, SerializesModels;

    public 
        $subject,
        $precontent,
        $banner,
        $title,
        $content,
        $button,
        $reason,
        $unsubscribeUrl,
        $file,
        $rep;


    //setter and getter
    public function setSubject($subject){
        $this->subject = $subject;
    }
    public function getSubject(){
        return $this->subject;
    }
    
    public function setPrecontent($precontent){
        $this->precontent = $precontent;
    }
    public function getPrecontent(){
        return $this->precontent;
    }
    
    public function setBanner($banner){
        $this->banner = $banner;
    }
    public function getBanner(){
        return $this->banner;
    }
    
    public function setTitle($title){
        $this->title = $title;
    }
    public function getTitle(){
        return $this->title;
    }
    
    public function setContent($content){
        $this->content = $content;
    }
    public function getContent(){
        return $this->content;
    }
    
    public function addButton($button){
        if(empty($this->button)){
            $this->button = [$button];
        }
        else{
            $this->button[] = $button;
        }
    }
    public function getButton(){
        return $this->button;
    }
    
    public function setReason($reason){
        $this->reason = $reason;
    }
    public function getReason(){
        return $this->reason;
    }

    public function setReplyTo($email=''){
        $this->rep = $email;
    }

    
    public function setUnsubscribeUrl($unsubscribeUrl){
        $this->unsubscribeUrl = $unsubscribeUrl;
    }
    public function getUnsubscribeUrl(){
        return $this->unsubscribeUrl;
    }
    
    public function addFile($file){
        if(empty($this->file)){
            $this->file = [$file];
        }
        else{
            $this->file[] = $file;
        }
    }





    public function build(){
        $out = [];
        if(!empty($this->precontent)){
            $out['precontent'] = $this->precontent;
        }
        if(!empty($this->banner)){
            $out['banner'] = $this->banner;
        }
        if(!empty($this->title)){
            $out['title'] = $this->title;
        }
        if(!empty($this->content)){
            $out['content'] = $this->content;
        }
        if(!empty($this->button)){
            $out['button'] = $this->button;
        }
        if(!empty($this->reason)){
            $out['reason'] = $this->reason;
        }
        if(!empty($this->unsubscribeUrl)){
            $out['unsubscribeUrl'] = $this->unsubscribeUrl;
        }

        $output = $this
            ->subject($this->subject)
            ->view('main::mail.index')
            ->with($out);

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