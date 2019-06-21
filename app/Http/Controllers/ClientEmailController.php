<?php

namespace App\Http\Controllers;


use App\Classes\Excel\ClientListImport;
use App\Classes\Mailers\ContactMailer;
use App\Commands\Emails\SendDirectDebitInstructionsCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class ClientEmailController
 * @package App\Http\Controllers
 */
class ClientEmailController extends Controller
{

    use DispatchesJobs;


    /**
     * @param $index
     * @param ClientListImport $import
     * @return $this
     */
    public function directDebitEmail($index, ClientListImport $import, ContactMailer $mailer)
    {

        die('not currently used');


        // get the results
        $results = $import->get();

        if($index+1 > count($results))
            die('All records complete');


       // Get the row we need
        $client= $results[$index];


        $name = "chris";
        $email = "chris@madebyspoken.co.uk";



        $this->dispatch(new SendDirectDebitInstructionsCommand($name, $email));



    }

}
