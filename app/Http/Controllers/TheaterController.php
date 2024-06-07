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

    public function show(): View {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $theater = Theater::find(request('id'));

        if (!$theater) {
            abort(404);
        }

        $seats = $theater->seats()->get();

        return view('theater.show')->with('theater', $theater)->with('seats', $seats);
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
}
