<?php
//reformat khusus utk google email
function mail_reformat($email){
  $exp = explode('@', $email);
  if(count($exp) <> 2){
    return false;
  }

  $name = $exp[0];
  $host = $exp[1];

  if(in_array($host, ['gmail.com'])){
    $name = str_replace(".", "", $name);

    $n = strpos($name, "+");
    if($n){
      $name = substr($name, 0, $n);
    }
  }
  $email = $name."@".$host;
  return $email;
}

function checkSmtp(){
    try{
        $transport = new Swift_SmtpTransport(config('mail.host'), config('mail.port'), config('mail.encryption'));
        $transport->setUsername(config('mail.username'));
        $transport->setPassword(config('mail.password'));
        $mailer = new Swift_Mailer($transport);
        $mailer->getTransport()->start();
        return false;
    } catch (Swift_TransportException $e) {
        return $e->getMessage();
    } catch (Exception $e) {
      return $e->getMessage();
    } 
}

function hideEmail($email, $shown_char=3){
  $pch = explode('@', $email);
  if(count($pch) == 2){
    $n = strlen($pch[0]);
    $a = substr($pch[0], 0, $shown_char);
    $sisa = $n - $shown_char;
    if($sisa > 0){
      for($i=0; $i<$sisa; $i++){
        $a.= '*';
      }
    }

    $email = $a.'@'.$pch[1];
  }
  else{
    $email = $email;
  }

  return $email;
}
