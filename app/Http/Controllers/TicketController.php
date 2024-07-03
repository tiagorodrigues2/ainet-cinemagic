<?php

namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

class TicketController extends \Illuminate\Routing\Controller
{
    public function ticket(int $id): View
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            abort(404, 'Ticket not found');
        }

        return view('tickets.ticket')->with('ticket', $ticket);
    }

    public function scan(): View {
        if (!Auth::check() || Auth::user()->isCustomer()) {
            abort(403, 'Unauthorized action.');
        }

        $success = Session::get('success');
        $error = Session::get('error');
        $ticket = Session::get('ticket');

        return view('tickets.scan')->with('success', $success)->with('erro', $error)->with('ticket', $ticket);
    }

    public function scanTicket(Request $request): RedirectResponse {
        if (!Auth::check() || Auth::user()->isCustomer()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'ticket_id' => 'required|integer'
        ],
        [
            'ticket_id.required' => 'Ticket number is required',
            'ticket_id.integer' => 'Ticket number must be an integer'
        ]);

        $ticket = Ticket::where('id', $request->ticket_id)->where('status', 'valid')->first();

        if (!$ticket) {
            Session::flash('error', 'Ticket not found or invalid');
            return redirect()->route('tickets.scan');
        }

        $ticket->status = 'invalid';
        $ticket->save();

        Session::flash('ticket', $ticket);
        Session::flash('success', 'Ticket scanned successfully!');
        return redirect()->route('tickets.scan');
    }
}
