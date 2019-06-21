<?php namespace App\Classes\Mailers\Contracts;



interface MailerServiceContract
{

    public function send($view, $view_data, $subject, $to_name, $to_email, $from_name, $from_email, $bcc_emails=NULL);
}

