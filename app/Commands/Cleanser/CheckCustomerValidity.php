<?php

namespace App\Commands\Cleanser;

use App\Commands\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CheckCustomerValidity  extends Command/* implements ShouldQueue*/
{

    use DispatchesJobs;

    protected $first_row;
    protected $second_row;
    protected $email_kill_words = ['.ru', 'russia', 'hotmails', '.pl', '.top'];
    protected $firstname_kill_words = [];
    protected $ignore_next_row = false;

    /**
     * @param $first_row
     * @param $second_row
     */
    function __construct($first_row, $second_row)
    {
        $this->first_row = $first_row;
        $this->second_row = $second_row;
    }



    /**
     * We will only ever keep the first row!
     *
     * @return void
     */
    public function handle()
    {
        $passed = $this->checkEmailKillWords();

        if($passed)
            $passed = $this->checkNameKillWords();

        if($passed)
            $passed = $this->checkUppercaseLetters();

        if($passed){
            $passed = $this->checkForDuplicateRows();
        }

        if($passed){
            echo "Passed ->";
            $this->dispatch(new WriteCustomerToFile($this->first_row, 'passed.csv'));

        }
        else{

            echo "*** Failed ->";
            $this->dispatch(new WriteCustomerToFile($this->first_row, 'failed.csv'));
        }


        // Output what happened
        echo $this->first_row->customerfirstname . " " . $this->first_row->email . " " . $this->first_row->address1 . "\n";




        return $this->ignore_next_row;



    }

    /**
     * @return bool
     */
    private function checkNameKillWords(){

        // Email kill words
        foreach($this->firstname_kill_words as $kill_word) {
            if (strpos($this->first_row->customerfirstname, $kill_word) > 0) {

                return false;

            }
        }
        return true;
    }

    /**
     * @return bool
     */
    private function checkEmailKillWords(){

        // Email kill words
        foreach($this->email_kill_words as $kill_word)

            if(strpos($this->first_row->email, $kill_word)>0){
                return false;

            }

        return true;
    }

    /**
 * @return bool
 */
    private function checkUppercaseLetters(){

        // Does it end with two uppercase letters
        $array_length = strlen($this->first_row->customerfirstname)-1;
        $last_two_letters = substr($this->first_row->customerfirstname, $array_length-1, $array_length);

        // Fail if last two letters are uppercase and the whole thing is not uppercase
        if(mb_strtoupper($last_two_letters)===$last_two_letters && !(mb_strtoupper($this->first_row->customerfirstname)===$this->first_row->customerfirstname)){
            return false;
        }

        return true;
    }


    /**
     * @return bool
     */
    private function checkForDuplicateRows(){

            if($this->first_row->customerfirstname == $this->second_row->customerfirstname){

                if($this->first_row->address1 != NULL || $this->first_row->address2 != NULL){
                    $this->ignore_next_row=true;
                    return true;

                }

                return false;

            }

        return true;

    }

}


