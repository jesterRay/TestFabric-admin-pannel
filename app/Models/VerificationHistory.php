<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class VerificationHistory extends Model
{
    public function getAll(){
        $verification_history = DB::select("Select QR_CODE, IP, country, state,city,location,date_time,method FROM verification_history");
        return $verification_history;
    }

    public function getVerificationHistoryForDataTable(){
        try {
            $query = DB::table('verification_history');
    
            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getCountries(){
        $countries = DB::table('verification_history')
        ->select('country')
        ->groupBy('country')
        ->pluck('country')
        ->toArray();
        return $countries;
    }
    
    public function getCountryCount(){
        $countries = $this->getCountries();
        $countryCountData = [];
        foreach($countries as $country) {
            $countryName = is_object($country) ? $country->country : $country;
            
            $count = DB::table('verification_history')
                ->where('country', $countryName)
                ->count();
                
            $countryCountData[] = $count;
        }
        return $countryCountData;
    }

    public function getVerificationCount(){
        $validCount = DB::select("SELECT COUNT(*) as count FROM verification_history WHERE valid = 2");
        $invalidCount = DB::select("SELECT COUNT(*) as count FROM verification_history WHERE valid = 0");

        return [$validCount[0]->count,$invalidCount[0]->count];

    }
}
