<?php namespace App\Classes\Mailers;
use App\Classes\Mailers\Contracts\MailerServiceContract;
use Mail;
use Illuminate\Mail\Message;
/**
 * Class MailerService
 * @package App\Classes\Mailers
 */
class MailerService implements  MailerServiceContract
{


    /**
     * @param $view
     * @param $view_data
     * @param $subject
     * @param $to_name
     * @param $to_email
     * @param $from_name
     * @param $from_email
     * @param $bcc_emails
     */
    public function send($view, $view_data, $subject, $to_name, $to_email, $from_name, $from_email, $bcc_emails=NULL)
    {

        $mailer_data = ['subject'=>$subject,
                        'to_name'=>$to_name,
                        'to_email'=>$to_email,
                        'from_name'=>$from_name,
                        'bcc_emails'=>$bcc_emails,
                        'from_email'=>$from_email];

            // Add back to queue (NESTING ERROR MESSAGE)
            \Mail::send($view, $view_data,function($message) use ($mailer_data){

            // Add from
            $message->from($mailer_data['from_email'], $mailer_data['from_name']);

            // Who are we sending to
            $recipients = [];
            $recipients[] = $mailer_data['to_email'];

            // Do we have bcc emails, merge..
            if(is_array($mailer_data['bcc_emails']) && count($mailer_data['bcc_emails'])>0){
              $recipients = array_merge($recipients, $mailer_data['bcc_emails']);
            }

            // Add to
            //$message->to($mailer_data['to_email'], $mailer_data['to_name']);
             $message->to($recipients);

            // Add the subject
            $message->subject($mailer_data['subject']);

        });

    }


}
