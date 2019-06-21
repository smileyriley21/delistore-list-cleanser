<?php namespace App\Classes\Mailers\Contracts;

interface ContactMailerContract {

    /**
     * @param MailerServiceContract $mailer
     */
    public function __construct(MailerServiceContract $mailer);


}