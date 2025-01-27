<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function getOrderHeading(){
        try {

            $heading = DB::select("SELECT * FROM testfabtics_order_heading");
            return  $heading ? $heading[0] : null;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateOrderHeading($id, $data){ 
        try { 
            return DB::table('testfabtics_order_heading')
                ->where('id', $id)
                ->update([
                    'heading_1' => $data['heading_1'],
                    'heading_2' => $data['heading_2']
                ]); 
        } catch (\Throwable $th) { 
            throw $th; 
        } 
    }




}
