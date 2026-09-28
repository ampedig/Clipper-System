<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendTelegramMessageJob;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'min:10', 'max:20', 'regex:/^[0-9+\s\-]{10,20}$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'clipper',
        ]);

        $text = "👤 <b>Member Baru Terdaftar</b>\n\n";
        $text .= "<b>Nama:</b> {$user->name}\n";
        $text .= "<b>Email:</b> {$user->email}\n";
        $text .= "<b>WhatsApp:</b> {$user->whatsapp}";

        SendTelegramMessageJob::dispatch($text, config('telegram.topics.user'));

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('app.home', absolute: false));
    }
}
