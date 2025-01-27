<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function heading(){
        try {
            
            $orderHeading = (new Order)->getOrderHeading();
            return view('admin.order.edit-heading')->with(["heading" => $orderHeading]);

        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }


    public function headingUpdate(Request $request, $id){
        try {
            $validated = $request->validate([
                'heading_1' => 'required|string|max:500',
                'heading_2' => 'required|string|max:500',
            ]);

            (new Order)->updateOrderHeading($id,$validated);
            return redirect()->route('order.heading.index')->with("success","Order heading update successfully");

        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }
}
