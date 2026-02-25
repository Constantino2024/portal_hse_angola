<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;

class NewsletterController extends Controller
{
    public function confirm(string $token)
    {
        $subscriber = Subscriber::where('unsubscribe_token', $token)->firstOrFail();

        $subscriber->verified_at = now();
        $subscriber->is_active = true;
        $subscriber->save();

        return redirect()->route('home')->with('success', 'Inscrição confirmada com sucesso!');
    }

    public function unsubscribe(string $token)
    {
        $subscriber = Subscriber::where('unsubscribe_token', $token)->firstOrFail();

        $subscriber->is_active = false;
        $subscriber->save();

        return redirect()->route('home')->with('success', 'Você foi removido da newsletter.');
    }
}
