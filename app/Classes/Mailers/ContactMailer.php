<?php namespace App\Classes\Mailers;

use Illuminate\Database\Eloquent\Collection;
use App\Classes\Mailers\Contracts\MailerServiceContract;
use App\Classes\Mailers\Contracts\ContactMailerContract;

/**
 * For emailing contacts
 *
 * Class ContactMailer
 * @package App\Classes\Mailers
 */
class ContactMailer implements ContactMailerContract
{

    /**
     * @var MailerServiceContract
     */
    protected $mailer;
    /**
     * @var array
     */
    protected $default_settings = [];

    /**
     * Pass a mailer service for
     *
     * @param MailerServiceContract $mailer
     */
    public function __construct(MailerServiceContract $mailer)
    {

        $this->mailer = $mailer;

        $this->default_settings = [
            'from_name' => \Config::get('myapp.company_name'),
            'from_email' => \Config::get('myapp.system_email')
        ];

    }

    /**
     * @param $name
     * @param $email
     */
    public function send_direct_debit_instructions($name, $email){

        // Prepare data for the email
        $data = [
            'name' => $name,
            'email'  => $email

        ];

        // And send it...
        $this->mailer->send('emails.direct-debit',  // Template
            $data,                                                               // Data
            \Config::get('myapp.company_name') . ' Direct Debit Setup' ,         // Subject
            $name,                                                               // Contacts name
            $email,                                                              // Contacts Email
            $this->default_settings['from_name'],                                // From name
            $this->default_settings['from_email']                                // From email
        );

    }




}