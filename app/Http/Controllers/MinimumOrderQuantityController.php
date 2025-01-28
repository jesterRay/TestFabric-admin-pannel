<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MinimumOrderQuantity;

class MinimumOrderQuantityController extends Controller
{

    public function index(Request $request){
        try {
            if ($request->ajax()) {
                return (new MinimumOrderQuantity)->getMinimumOrderQuantityForDataTable();
            }
            return view('admin.min-order-quantity.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function create(){
        return view('admin.min-order-quantity.create');
    }

    public function edit($id){
        try {
            $minQuantity = (new MinimumOrderQuantity)->getMinimumOrderQuantityById($id);

            if (!$minQuantity) {
                return redirect()->back()->with(['error' => 'Minimum Order Quantity record not found.']);
            }

            return view('admin.min-order-quantity.edit')->with(['minQuantity' => $minQuantity]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function save(Request $request){
        try {
            $validated = $request->validate([
                'Min__Name' => 'required|string|max:200',
                'Min__Status' => 'required|in:Show,Hide',
                'excel' => 'nullable|integer',
            ]);

            $result = (new MinimumOrderQuantity)->addMinimumOrderQuantity($validated);

            return redirect()->route('min-order-quantity.index')
                ->with(['success' => 'Minimum Order Quantity added successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, $id){
        try {
            $validated = $request->validate([
                'Min__Name' => 'required|string|max:200',
                'Min__Status' => 'required|in:Show,Hide',
                'excel' => 'nullable|integer',
            ]);

            $result = (new MinimumOrderQuantity)->updateMinimumOrderQuantity($id, $validated);

            return redirect()->route('min-order-quantity.index')
                ->with(['success' => 'Minimum Order Quantity updated successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id){
        try {
            $result = (new MinimumOrderQuantity)->deleteMinimumOrderQuantity($id);

            return redirect()->route('min-order-quantity.index')
                ->with(['success' => 'Minimum Order Quantity deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()]);
        }
    }
}
