<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\DownloadAction;

class DownloadController extends Controller
{
    public function downloadAirtime(DownloadAction $downloadAction){
        $responseArray = $downloadAction->downloadAirtime();
        list($file, $name) = $responseArray;
        return response()->download($file, $name, DownloadAction::HEADER);

    }

    public function downloadData(DownloadAction $downloadAction){
        $responseArray = $downloadAction->downloadData();
        list($file, $name) = $responseArray;
        return response()->download($file, $name, DownloadAction::HEADER);
    }
}
