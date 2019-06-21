<?php

namespace App\Console\Commands;

use App\Classes\Excel\ClientListImport;
use App\Commands\Emails\SendDirectDebitInstructionsCommand;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;


class SendDirectDebitInstructions extends Command
{

    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-direct-debit-instructions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send direct debit instructions to clients';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ClientListImport $import)
    {




        // get the results
        $results = $import->get();

        foreach($results as $result){



            $this->dispatch(new SendDirectDebitInstructionsCommand($result->first_name, $result->email));

        }


    }
}
