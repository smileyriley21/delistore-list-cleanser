<?php

namespace App\Commands\Cleanser;

use App\Commands\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class WriteCustomerToFile  extends Command/* implements ShouldQueue*/
{


    protected $file_name;
    protected $row;

    /**
     * @param $row
     * @param $file_name
     */
    function __construct($row, $file_name)
    {
        $this->file_name = $file_name;
        $this->row = $row;
    }


    /**
     * We will only ever keep the first row!
     *
     * @return void
     */
    public function handle()
    {

        $filename =  storage_path('/app/exports/') . $this->file_name;

        if(!file_exists($filename)){
            file_put_contents($filename, $this->prepareHeaders().PHP_EOL , FILE_APPEND | LOCK_EX);
        }

        file_put_contents($filename, $this->prepareRow().PHP_EOL , FILE_APPEND | LOCK_EX);
       // Storage::append($filename, $this->prepareRow());

    }

    /**
     *
     */
    private function prepareRow(){

        $data = "";
        $lower_case_fields = ['email', 'login'];
        $upper_case_words = ['customerfirstname', 'customerlastname', 'firstname', 'lastname', 'companyname', 'address1', 'address2','address3', 'town', 'county'];
        $upper_case_all = ['postcode'];


        foreach($this->row->all() as $key=>$value){

            if(in_array($key, $lower_case_fields)){
                $value = strtolower($value);
            }
            else if(in_array($key, $upper_case_words)){
                $value = ucwords(strtolower($value));
            }
            else if(in_array($key, $upper_case_all)){
                $value = strtoupper($value);
            }

            if($value=="NULL" || $value=="Null" || $value=="null" || $value==NULL){
                $value="";
            }


            $data .= $value . ";";
        }

        $data = rtrim($data,',');

        return $data;

    }

    /**
     *
     */
    private function prepareHeaders(){

        $data = "";
        foreach($this->row->all() as $key=>$value){


            $data .= $key . ";";
        }

        $data = rtrim($data,',');

        return $data;

    }


}


