<?php

namespace App\Http\Controllers;

use App\Models\VerificationHistory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VerificationHistoryController extends Controller
{
    public function index(Request $request){

        if($request->ajax()){
            $verification_history = (new VerificationHistory)->getVerificationHistoryForDataTable();
            return $verification_history;
        }

        $countries = (new VerificationHistory)->getCountries();
        $verificationCount = (new VerificationHistory)->getVerificationCount();
        $countryCountData = (new VerificationHistory)->getCountryCount();

        return view('admin.dashboard.index')
                ->with([
                    'countries' => $countries,
                    'countryCountData' => $countryCountData,
                    'verificationCount' => $verificationCount,
                ]);
    
    }


}
