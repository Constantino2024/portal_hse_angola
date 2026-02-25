<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Subscriber;
use App\Mail\ConfirmSubscription;
use Illuminate\Support\Str;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = Subscriber::firstOrNew(['email' => $request->email]);

        if ($subscriber->exists && $subscriber->verified_at && $subscriber->is_active) {
            return back()->with('success', 'Este email já está inscrito na newsletter.');
        }

        $subscriber->is_active = false;
        $subscriber->verified_at = null;
        $subscriber->unsubscribe_token = Str::random(32);
        $subscriber->save();

        Mail::to($subscriber->email)->send(new ConfirmSubscription($subscriber));

        return back()->with('success', 'Confirmar inscrição: verifique seu email.');
    }
}
