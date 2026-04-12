<?php

namespace App\Actions;
use Illuminate\Support\Facades\Log;

class DownloadAction
{
    const HEADER  = ['Content-Type => application/csv'];

    public function downloadData(){
        $file = public_path()."/DATATEMPLATE.csv";
        $name = "data_template.csv";
        return [$file,$name];
    }

    public function downloadAirtime(){
        $file = public_path()."/AIRTIMETEMPLATE.csv";
        $name = "airtime_template.csv";
        return [$file,$name];
    }

}
