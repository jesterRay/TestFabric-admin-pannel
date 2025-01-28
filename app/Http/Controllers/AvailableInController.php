<?php

namespace App\Http\Controllers;

use App\Models\AvailableIn;
use Illuminate\Http\Request;

class AvailableInController extends Controller
{


    public function index(Request $request){
        try {
            if ($request->ajax()) {
                return (new AvailableIn)->getAvailableInForDataTable();
            }
            return view('admin.available-in.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function create(){
        return view('admin.available-in.create');
    }

    public function edit($id){
        try {
            $availableIn = (new AvailableIn)->getAvailableInById($id);

            if (!$availableIn) {
                return redirect()->back()->with(['error' => 'Available In record not found.']);
            }

            return view('admin.available-in.edit')->with(['availableIn' => $availableIn]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function save(Request $request){
        try {
            $validated = $request->validate([
                'Available__Name' => 'required|string|max:200',
                'Available__Status' => 'required|in:Show,Hide',
                'excel' => 'nullable|numeric',
            ]);

            $result = (new AvailableIn)->addAvailableIn($validated);

            return redirect()->route('available-in.index')
                ->with(['success' => 'Available In added successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, $id){
        try {
            $validated = $request->validate([
                'Available__Name' => 'required|string|max:200',
                'Available__Status' => 'required|in:Show,Hide',
                'excel' => 'nullable|integer',
            ]);

            $result = (new AvailableIn)->updateAvailableIn($id, $validated);

            return redirect()->route('available-in.index')
                ->with(['success' => 'Available In updated successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id){
        try {
            $result = (new AvailableIn)->deleteAvailableIn($id);

            return redirect()->route('available-in.index')
                ->with(['success' => 'Available In deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()]);
        }
    }


    
}
