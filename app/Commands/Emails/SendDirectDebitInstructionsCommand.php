<?php

namespace App\Commands\Emails;

use App\Commands\Command;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SendDirectDebitInstructionsCommand
 * @package app\Commands\Emails
 */
class SendDirectDebitInstructionsCommand  extends Command implements ShouldQueue
{


    /**
     * @var
     */
    protected $name;
    /**
     * @var
     */
    protected $email;

    /**
     * @param $name
     * @param $email
     */
    function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "Send to " . $this->name . ":" . $this->email . "\n";

        // Get the service classes
        $service = app('App\Classes\Mailers\ContactMailer');

        $service->send_direct_debit_instructions($this->name, $this->email);
    }
}


