<?php

namespace Hcode;

use Rain\Tpl;
use PHPMailer;

class Mailer {

    const USERNAME = "devtarcisio@gmail.com";
    const PASSWORD = "";
    const NAME_FROM = "Hcode Store";

    private $mail;

    public function __construct($toAddress, $toName, $subject, $tplName, $data = array())
    {

        $config = array(
            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/admin/email/",
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
            "debug"         => false
        );

        Tpl::configure( $config );

        $tpl = new Tpl;

        foreach ($data as $key => $value) {
            $tpl->assign($key, $value);
        }

        $html = $tpl->draw($tplName, true);


        $this->mail = new \PHPMailer;

        $this->mail->isMail();
        $this->mail->SMTPDebug = 0;       
        $this->mail->Host = 'smtp.gmail.com'; 
        $this->mail->Port = 587;
        //$this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->SMTPAuth = true;
        
        $this->mail->Username = Mailer::USERNAME;
        $this->mail->Password = Mailer::PASSWORD;

        
        $this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);        
        //$mail->addReplyTo('devtarcisio@gmail.com', 'Dicont SC');

        
        $this->mail->addAddress($toAddress, $toName);        
        $this->mail->Subject = $subject;        
        $this->mail->msgHTML($html);


        $this->mail->AltBody = 'This is a plain-text message body';


        //Anexo
        //$this->mail->addAttachment('usuarios.csv');
             
    }

    public function send(){

        return $this->mail->send();

        /*
        if (!$this->mail->send()) {
            echo 'Houve um erro: '. $this->mail->ErrorInfo;
        } else {
            echo 'E-mail enviado!';
        } 
        */  

    }

}


?>
