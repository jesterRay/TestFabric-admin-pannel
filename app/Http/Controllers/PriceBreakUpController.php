<?php

namespace App\Http\Controllers;

use App\Models\PriceBreakUp;
use Illuminate\Http\Request;

class PriceBreakUpController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return (new PriceBreakUp)->getPriceBreakUpsForDataTable();
            }
            return view('admin.price-break-up.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function create()
    {
        return view('admin.price-break-up.create');
    }

    public function edit($id)
    {
        try {
            $priceBreakUp = (new PriceBreakUp)->getPriceBreakUpById($id);

            if (!$priceBreakUp) {
                return redirect()->back()->with(['error' => 'Price Break Up record not found.']);
            }

            return view('admin.price-break-up.edit')->with(['priceBreakUp' => $priceBreakUp]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function save(Request $request)
    {
        try {
            $validated = $request->validate([
                'price__Title' => 'required|string|max:255',
                'price__Units' => 'required|integer',
                'price__Range_Format' => 'required|integer',
                'price__Status' => 'required|in:Show,Hide',
            ]);

            (new PriceBreakUp)->addPriceBreakUp($validated);

            return redirect()->route('price-break-up.index')
                ->with(['success' => 'Price Break Up added successfully.']);
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
                'price__Title' => 'required|string|max:255',
                'price__Units' => 'required|integer',
                'price__Range_Format' => 'required|integer',
                'price__Status' => 'required|in:Show,Hide',
            ]);

            (new PriceBreakUp)->updatePriceBreakUp($id, $validated);

            return redirect()->route('price-break-up.index')
                ->with(['success' => 'Price Break Up updated successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            (new PriceBreakUp)->deletePriceBreakUp($id);

            return redirect()->route('price-break-up.index')
                ->with(['success' => 'Price Break Up deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()]);
        }
    }
}
