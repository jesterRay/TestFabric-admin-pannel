<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return (new Unit)->getUnitsForDataTable();
            }
            return view('admin.units.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function edit($id)
    {
        try {
            $unit = (new Unit)->getUnitById($id);

            if (!$unit) {
                return redirect()->back()->with(['error' => 'Unit record not found.']);
            }

            return view('admin.units.edit')->with(['unit' => $unit]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function save(Request $request)
    {
        try {
            $validated = $request->validate([
                'priceunit__Name' => 'required|string|max:100',
                'priceunit__Sequence' => 'required|integer',
                'priceunit__Status' => 'required|in:Show,Hide',
            ]);

            $result = (new Unit)->addUnit($validated);

            return redirect()->route('unit.index')
                ->with(['success' => 'Unit added successfully.']);
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
                'priceunit__Name' => 'required|string|max:100',
                'priceunit__Sequence' => 'required|integer',
                'priceunit__Status' => 'required|in:Show,Hide',
            ]);

            $result = (new Unit)->updateUnit($id, $validated);

            return redirect()->route('unit.index')
                ->with(['success' => 'Unit updated successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = (new Unit)->deleteUnit($id);

            return redirect()->route('unit.index')
                ->with(['success' => 'Unit deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()]);
        }
    }
}
