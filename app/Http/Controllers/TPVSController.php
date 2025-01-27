<?php

namespace App\Http\Controllers;

use App\Models\TPVS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TPVSController extends Controller
{


    public function content(){
        try {
            $content = (new TPVS)->getTPVSContent();
            return view('admin.tpvs.content.edit')->with(['content' => $content]);

        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }


    public function contentUpdate(Request $request, $id){
        try {
            // Validate the request
            $validated = $request->validate([
                'heading_1' => 'required|string|max:255',
                'heading_2' => 'required|string|max:255',
                'pink_message' => 'required|string|max:600',
                'yellow_message' => 'required|string|max:600',
                'green_message' => 'required|string|max:600',
                'imgfile' => 'nullable|image|mimes:jpg|max:2048', // Validate image
            ]);

            (new TPVS)->updateTPVSContent($id,$validated,$request->file('imgfile'));

            return redirect()->route('tpvs.content')->with(['success' => 'TPVS content updated Successfully']);

        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }


    
    public function info(Request $request){
        try {
            if($request->ajax()){
                $info = (new TPVS)->getTPVSInfoForDataTable();
                return $info;
            }
        
            return view("admin.tpvs.info.index");
            
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }
    

    public function createSerial(){
        return view('admin.tpvs.serial.create');
    }


    public function saveSerial(Request $request){
        $validated = $request->validate([
            'start' => 'required|integer|min:1',
            'end' => 'required|integer|min:1|gte:start'
        ]);

        try {
            $result = (new TPVS)->insertSerialRange(
                $validated['start'], 
                $validated['end']
            );

            return redirect()->back()->with(['success' => 'Serial number added']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function indexSerial(Request $request){
        try {

            if($request->ajax()){
                $serials = (new TPVS)->getSerialForDataTable($request);
                return $serials;
            }
            return view("admin.tpvs.serial.index");

        } catch (\Throwable $th) {
            dd($th->getMessage());

            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }


    public function updateSerial(Request $request){
        try {

            $id = $request->input('id');
            $checked = $request->input('checked');

            $isUpdate = (new TPVS)->updateCheckedStatus($id,$checked);
            if($isUpdate){
                return response()->json(['success' => true, 'message' => 'Row updated successfully!']);
            }

            return response()->json(['success' => false, 'message' => 'Record not found.']);

        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
            
        }

    }





}
