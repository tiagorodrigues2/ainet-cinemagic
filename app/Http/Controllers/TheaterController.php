<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\Theater;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Seat;

class TheaterController extends \Illuminate\Routing\Controller
{
    public function index(): View
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $theaters = Theater::all();

        return view('theater.index')->with('theaters', $theaters);
    }

    public function new(): View
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('theater.create');
    }

    public function create(Request $request) {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rows' => 'required|numeric|min:1|max:26',
            'cols' => 'required|numeric|min:1',
        ], [
            'name.required' => 'The name is required',
            'name.max' => 'The name must have a maximum of 255 characters',
            'photo.image' => 'The photo must be an image',
            'photo.mimes' => 'The photo must be a jpeg, png, jpg, gif or svg',
            'photo.max' => 'The photo must have a maximum size of 2048',
            'rows.required' => 'The number of rows is required',
            'rows.numeric' => 'The number of rows must be a number',
            'rows.min' => 'The number of rows must be at least 0',
            'rows.max' => 'The number of rows must be at most 26',
            'cols.required' => 'The number of columns is required',
            'cols.numeric' => 'The number of columns must be a number',
            'cols.min' => 'The number of columns must be at least 0',
        ]);

        try {
            \DB::beginTransaction();
            $theater = new Theater();

            if ($request->has('photo')) {
                $photo = $request->file('photo');
                $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('theater', $photoName, 'public');
                $theater->photo_filename = str_replace('theater/', '', $photoPath);
            }

            $theater->name = $request->input('name');
            $theater->save();

            for ($r = 1; $r <= $request->input('rows'); $r++) {

                $rowChar = chr(64 + $r);

                for ($c = 1; $c <= $request->input('cols'); $c++) {
                    $seat = new Seat();
                    $seat->theater_id = $theater->id;
                    $seat->row = $rowChar;
                    $seat->seat_number = $c;
                    $seat->save();
                }
            }

            \DB::commit();
            Session::flash('success', 'Theater created successfully!');
            return redirect()->route('theaters.show', ['id' => $theater->id]);
        } catch (\Exception $e) {
            \DB::rollBack();
            Session::flash('error', 'Failed to create theater: ' . $e->getMessage());
        }

        return redirect()->route('theaters');
    }

    public function show(): View {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $theater = Theater::find(request('id'));

        if (!$theater) {
            abort(404);
        }

        $seats = $theater->seats()->orderBy('row')->orderBy('seat_number')->get();
        $seat_rows = $seats->groupBy('row');

        $numberOfColumns = $seat_rows->map(function ($row) {
            return $row->count();
        })->max();


        return view('theater.show')->with('theater', $theater)->with('seats', $seat_rows)->with('numberOfColumns', $numberOfColumns);
    }

    public function save(int $id, Request $request): RedirectResponse {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'max:255',
        ], [
            'id.required' => 'The theater ID is required',
            'id.numeric' => 'The theater ID must be a number',
            'photo.image' => 'The photo must be an image',
            'photo.mimes' => 'The photo must be a jpeg, png, jpg, gif or svg',
            'photo.max' => 'The photo must have a maximum size of 2048',
        ]);

        $theater = Theater::find($id);
        $alterouFoto = false;

        if (!$theater) {
            abort(404);
        }

        if ($request->has('name')) {
            $theater->name = $request->input('name');
            Session::flash('success', 'Name saved successfully!');
        }

        if ($request->has('photo')) {
            try {
                $photo = $request->file('photo');
                $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('theater', $photoName, 'public');
                $theater->photo_filename = str_replace('theater/', '', $photoPath);

                Session::flash('success', 'Photo saved successfully!');
                $alterouFoto = true;
            } catch (\Exception $e) {
                Session::flash('error', $e->getMessage());
            }
        }

        if ($request->has('name') || ($request->has('photo') || $alterouFoto)) {
            $theater->save();
        }

        return redirect()->route('theaters.show', ['id' => $id]);
    }

    public function addRow(Request $request): RedirectResponse {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'theater_id' => 'required|numeric',
        ], [
            'theater_id.required' => 'The theater ID is required',
            'theater_id.numeric' => 'The theater ID must be a number',
        ]);

        $theater = Theater::find($request->input('theater_id'));

        if (!$theater) {
            abort(404);
        }

        $seats = $theater->seats()->get();

        $nextRow = $this->getNextRow($seats);

        if (!$nextRow == chr(ord('Z') + 1)) {
            Session::flash('error', 'The theater is full and cannot add more rows');
            return redirect()->route('theaters.show', ['id' => $theater->id]);
        }

        for ($c = 1; $c <= $seats->max('seat_number'); $c++) {
            $seat = new Seat();
            $seat->theater_id = $theater->id;
            $seat->row = $nextRow;
            $seat->seat_number = $c;
            $seat->save();
        }

        Session::flash('success', 'Row added successfully!');

        return redirect()->route('theaters.show', ['id' => $theater->id]);
    }

    public function addCol(Request $request): RedirectResponse {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'theater_id' => 'required|numeric',
        ], [
            'theater_id.required' => 'The theater ID is required',
            'theater_id.numeric' => 'The theater ID must be a number',
        ]);

        $theater = Theater::find($request->input('theater_id'));

        if (!$theater) {
            abort(404);
        }

        $seats = $theater->seats()->get();
        $rows = $seats->groupBy('row');

        $nextCol = $this->getNextCol($seats);

        foreach ($rows as $row) {
            $seat = new Seat();
            $seat->theater_id = $theater->id;
            $seat->row = $row->first()->row;
            $seat->seat_number = $nextCol;
            $seat->save();
        }

        Session::flash('success', 'Column added successfully!');

        return redirect()->route('theaters.show', ['id' => $theater->id]);
    }

    public function deleteRow(int $id, string $row): RedirectResponse {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $theater = Theater::find($id);

        if (!$theater) {
            abort(404);
        }

        $seats = $theater->seats()->get();
        $rows = $seats->groupBy('row');

        if ($rows->count() == 1) {
            Session::flash('error', 'The theater must have at least one row');
            return redirect()->route('theaters.show', ['id' => $theater->id]);
        }

        $seats = $seats->filter(function ($seat) use ($row) {
            return $seat->row == $row;
        });

        if ($seats->count() == 0) {
            Session::flash('error', 'Row not found');
            return redirect()->route('theaters.show', ['id' => $theater->id]);
        }

        $screenings = $theater->screenings()->get();
        $tickets = $screenings->map(function ($screening) {
            return $screening->tickets()->get();
        })->flatten()->where('date', '>=', now());;

        $tickets = $tickets->filter(function ($ticket) {
            return $ticket->status == 'valid';
        });

        $tickets = $tickets->filter(function ($ticket) use ($seats) {
            return $seats->contains('id', $ticket->seat_id);
        });

        if ($tickets->count() > 0) {
            Session::flash('error', 'The row has tickets sold and cannot be deleted');
            return redirect()->route('theaters.show', ['id' => $theater->id]);
        }

        foreach ($seats as $seat) {
            $seat->delete();
        }

        Session::flash('success', 'Row deleted successfully!');
        return redirect()->route('theaters.show', ['id' => $theater->id]);
    }

    public function deleteCol(int $id, int $col): RedirectResponse {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $theater = Theater::find($id);

        if (!$theater) {
            abort(404);
        }

        $seats = $theater->seats()->get();
        $cols = $seats->groupBy('seat_number');

        if ($cols->count() == 1) {
            Session::flash('error', 'The theater must have at least one column');
            return redirect()->route('theaters.show', ['id' => $theater->id]);
        }

        $seats = $seats->filter(function ($seat) use ($col) {
            return $seat->seat_number == $col;
        });

        if ($seats->count() == 0) {
            Session::flash('error', 'Column not found');
            return redirect()->route('theaters.show', ['id' => $theater->id]);
        }

        $screenings = $theater->screenings()->get();
        $tickets = $screenings->map(function ($screening) {
            return $screening->tickets()->get();
        })->flatten()->where('date', '>=', now());

        $tickets = $tickets->filter(function ($ticket) {
            return $ticket->status == 'valid';
        });

        $tickets = $tickets->filter(function ($ticket) use ($seats) {
            return $seats->contains('id', $ticket->seat_id);
        });

        if ($tickets->count() > 0) {
            Session::flash('error', 'The column has tickets sold and cannot be deleted');
            return redirect()->route('theaters.show', ['id' => $theater->id]);
        }

        foreach ($seats as $seat) {
            $seat->delete();
        }

        return redirect()->route('theaters.show', ['id' => $theater->id]);
    }

    public function delete(int $id): RedirectResponse {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $theater = Theater::find($id);

        if (!$theater) {
            abort(404);
        }

        $screenings = $theater->screenings()->get();
        $seats = $theater->seats()->get();
        $tickets = $screenings->map(function ($screening) {
            return $screening->tickets()->get();
        })->flatten()->where('date', '>=', now());;

        $tickets = $tickets->filter(function ($ticket) {
            return $ticket->status == 'valid';
        });

        if ($tickets->count() > 0) {
            Session::flash('error', 'The theater has tickets sold and cannot be deleted');
            return redirect()->route('theaters.show', ['id' => $id]);
        }

        if (!$theater) {
            abort(404);
        }

        try {
            \DB::beginTransaction();

            foreach ($screenings as $screening) {
                $screening->delete();
            }

            foreach ($seats as $seat) {
                $seat->delete();
            }

            $theater->delete();

            \DB::commit();

            Session::flash('success', 'Theater deleted successfully!');
        } catch (\Exception $e) {
            \DB::rollBack();
            Session::flash('error', 'Failed to delete theater: ' . $e->getMessage());
        }

        return redirect()->route('theaters');
    }


    private function getNextRow($seats) {
        $rows = $seats->groupBy('row');

        for ($i = 1; $i <= 26; $i++) {
            $rowChar = chr(64 + $i);

            if (!isset($rows[$rowChar])) {
                return $rowChar;
            }
        }
    }

    private function getNextCol($seats) {
        $cols = $seats->groupBy('seat_number');

        for ($i = 1; $i <= 100; $i++) {
            if (!isset($cols[$i])) {
                return $i;
            }
        }
    }
}
