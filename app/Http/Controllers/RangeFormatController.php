<?php

namespace App\Http\Controllers;

use App\Models\RangeFormat;
use Illuminate\Http\Request;

class RangeFormatController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return (new RangeFormat)->getRangeFormatsForDataTable();
            }
            return view('admin.range-format.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function create()
    {
        return view('admin.range-format.create');
    }

    public function edit($id)
    {
        try {
            $rangeFormat = (new RangeFormat)->getRangeFormatById($id);

            if (!$rangeFormat) {
                return redirect()->back()->with(['error' => 'Range Format record not found.']);
            }

            return view('admin.range-format.edit')->with(['rangeformat' => $rangeFormat]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function save(Request $request)
    {
        try {
            $validated = $request->validate([
                'rangeformat__Name' => 'required|string|max:100',
                'rangeformat__Sequence' => 'required|integer',
                'rabgeformat__Status' => 'required|in:Show,Hide',
            ]);

            $result = (new RangeFormat)->addRangeFormat($validated);

            return redirect()->route('range-format.index')
                ->with(['success' => 'Range Format added successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'rangeformat__Name' => 'required|string|max:100',
                'rangeformat__Sequence' => 'required|integer',
                'rabgeformat__Status' => 'required|in:Show,Hide',
            ]);

            $result = (new RangeFormat)->updateRangeFormat($id, $validated);

            return redirect()->route('range-format.index')
                ->with(['success' => 'Range Format updated successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = (new RangeFormat)->deleteRangeFormat($id);

            return redirect()->route('range-format.index')
                ->with(['success' => 'Range Format deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()]);
        }
    }
}
