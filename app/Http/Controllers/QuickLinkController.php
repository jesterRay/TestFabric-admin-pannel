<?php

namespace App\Http\Controllers;

use App\Models\QuickLink;
use Illuminate\Http\Request;

class QuickLinkController extends Controller
{

    public function index(Request $request){
        try {
            if($request->ajax()){
                return (new QuickLink)->getQuickLinksForDataTable();
            }
            return view('admin.quick-link.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }
    
    public function create(){
        return view('admin.quick-link.create');
    }
    
    public function edit($id){
        try {
            $quickLink = (new QuickLink)->getQuickLinkById($id);
            
            if (!$quickLink) 
                return redirect()->back()->with(['error' => 'Quick Link not found.']);
    
            return view('admin.quick-link.edit')->with(['link' => $quickLink]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
    public function save(Request $request) {
        try {
            $validated = $request->validate([
                'links__Title' => 'required|string|max:225',
                'links__URL' => 'required|url|max:225',
                'links__Sequence' => 'required|numeric',
                'links__Status' => 'required|in:Show,Hide',
            ]);
    
            $result = (new QuickLink)->addQuickLink($validated);
    
            return redirect()->route('quick-link.index')
                ->with(['success' => 'Quick Link added successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()])
                ->withInput();
        }
    }
    
    public function update(Request $request, $id) {
        try {
            $validated = $request->validate([
                'links__Title' => 'required|string|max:100',
                'links__URL' => 'required|url|max:100',
                'links__Sequence' => 'required|numeric',
                'links__Status' => 'required|in:Show,Hide',
            ]);
    
            $result = (new QuickLink)->updateQuickLink($id, $validated);
    
            return redirect()->route('quick-link.index')
                ->with(['success' => 'Quick Link updated successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()])
                ->withInput();
        }
    }
    
    public function destroy($id) {
        try {
            $result = (new QuickLink)->deleteQuickLink($id);
    
            return redirect()->route('quick-link.index')
                ->with(['success' => 'Quick Link deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()]);
        }
    }


}
