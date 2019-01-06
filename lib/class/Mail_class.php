<?php

//###############################################################
// DESCRIPTION : mail_class
// DATE : 2012/05/11
// AUTEUR : CARRE Gaël / Kocka
//###############################################################

require_once('swiftmailer4.1.5/swift_required.php');


class Mail_class {
    var $objTransport;
    var $objMailer;
    var $objMessage;
    var $type = 'text';
    public $from_name = 'No Reply';
    public $from = '';
    public $sujet = 'sans sujet';
    public $message = 'sans message';
    public $base_url="";


    public function mail_class()
    {
        Swift_Preferences::getInstance()->setCharset('UTF-8');
        $this->objTransport = Swift_SmtpTransport::newInstance("127.0.0.1",25);
        if (PHP_OS == 'WINNT') $this->objTransport = Swift_MailTransport::newInstance();
        $this->objMailer = Swift_Mailer::newInstance($this->objTransport);
        $this->objMessage = Swift_Message::newInstance();
        $this->base_url = CONFIG::get("base_url");
        $from='noreply@'.$_SERVER["HTTP_HOST"];
    }

    public function Send($destinataire,$sujet='',$message='',$from='',$from_name='')
    {
        $this->SetSujet($sujet);
        $this->SetMessage($message);
        $this->SetFrom($from,$from_name);
        $fr = array($this->from=>$this->from_name);

        $this->objMessage->setFrom($fr)->setTo($this->constructDestinataire($destinataire))->setSubject($this->sujet)
                        ->setBody($this->createMessage($this->message));
        $resultat = $this->objMailer->send($this->objMessage);
        return $resultat;
    }

    public function SetFrom($from,$from_name)
    {
        if($from != '') $this->from = $from;
        if($from_name != '') $this->from_name = $from_name;
    }

    public function SetMessage($message)
    {
        if($message)
        {
            $this->message = $message;
        }
    }

    public function SetSujet($sujet)
    {
        if($sujet) $this->sujet = $sujet;
    }

    public function SetType($type)
    {
        if($type != null)
            $this->type = $type;

        return $this;
    }

    private function createMessage($message)
    {
        if($this->type == 'html')
        {

            $type = $this->objMessage->getHeaders()->get('Content-Type');

            $type->setValue('text/html');

            $content = "<html><header><style></style></header><body>";
                $content .= html_entity_decode($message);
            $content .="</body></html>";
            return $content;
        }
        else{
            return $message;
        }
    }

    private function constructDestinataire($destinataire)
    {
        $to = array();
        foreach(explode(',',$destinataire) as $mail)
        {
            $name = explode('@',$mail);
            $to[$mail] = $name[0];
        }

        return $to;
    }
}

?>
