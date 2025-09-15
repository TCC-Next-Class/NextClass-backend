<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TicketsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:create_organization',
        ]);

        $ticket = Ticket::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'expires_at' => now()->addDays(7),
        ]);

        $user = User::find($request->user_id);

        Mail::to($user->email)->send(new \App\Mail\TicketLinkMail($ticket));

        return response()->json(['message' => 'Ticket criado e enviado por e-mail.']);
    }

    public function use(Request $request, $uuid)
    {
        $ticket = Ticket::where('uuid', $uuid)->firstOrFail();

        if (!$ticket->isValid()) {
            return response()->json(['message' => 'Ticket inválido ou expirado.'], 400);
        }

        $request->validate([
            'organization_name' => 'required|string|max:255',
        ]);

        $organization = $ticket->user->organizations()->create([
            'name' => $request->organization_name,
        ]);

        $ticket->used = true;
        $ticket->save();

        return response()->json([
            'message' => 'Organização criada com sucesso.',
            'organization' => $organization,
        ]);
    }
}
