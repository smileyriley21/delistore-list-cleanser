<?php

namespace App\Console\Commands;

use App\Classes\Excel\ClientListImport;

use App\Commands\Cleanser\CheckCustomerValidity;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;


class CleanClientData extends Command
{

    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean-client-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean the client spreadsheet';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ClientListImport $import)
    {




        // get the results (used to have [0] at the send, assuming this is the first sheet?
        $results = $import->get();

        // The total results...
         $total_results = count($results);


        // The current row we are working on
        $current_row_number = 0;
        $next_row_number = 1;


        // Force total results to 10 for now
        //$total_results = 140;

        // Loop around each row
        while($current_row_number<$total_results-1){

            // Get the current and next row
            $current_row = $results[$current_row_number];
            $next_row = $results[$next_row_number];

            // Check for
            $ignore_next_row =  $this->dispatch(new CheckCustomerValidity($current_row, $next_row));

            // Increment as end of loop
            $current_row_number++;
            $next_row_number++;

            // And go one more as ignoring the next row as there was an address match..
            if($ignore_next_row){
                $current_row_number++;
                $next_row_number++;
            }

        }


    }
}
