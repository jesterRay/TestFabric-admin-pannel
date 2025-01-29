<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller{
        
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                // Fetch events for DataTable
                $events = (new Event)->getEventsForDataTable();
                return $events;
            }
            return view('admin.event.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function create()
    {
        return view('admin.event.create');
    }

    public function save(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'events__Name' => 'required|string|max:255',
                'events__Abbriviation' => 'required|string',
                'events__Url' => 'required|url|max:255',
                'events__Description' => 'required|string',
                'imgfile' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validate image
            ]);

            // Call the model function to save the data
            $result = (new Event)->addEvent($validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('event.index')->with(['success' => 'Event added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the event.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        try {
            // Call the model function to fetch the event data
            $event = (new Event)->getEventById($id);

            if (!$event) {
                return redirect()->back()->with(['error' => 'Event not found.']);
            }

            return view('admin.event.edit')->with([
                'event' => $event,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'events__Name' => 'required|string|max:255',
                'events__Abbriviation' => 'required|string|max:50',
                'events__Url' => 'required|url|max:255',
                'events__Description' => 'required|string',
                'imgfile' => 'image|mimes:jpg,jpeg,png|max:2048', // Validate image
            ]);

            // Call the model function to update the event
            $result = (new Event)->updateEvent($id, $validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('event.index')->with(['success' => 'Event updated successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error updating the event.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            // Call the model function to delete the event
            $result = (new Event)->deleteEvent($id);

            if ($result) {
                return redirect()->route('event.index')->with(['success' => 'Event deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Event could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
}
