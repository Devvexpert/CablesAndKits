<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use App\Models\UserKey;
use App\Traits\EncryptDecrypt;


class AuthenticatedSessionController extends Controller
{

    use EncryptDecrypt;
    /**
     * Display the login view.
     */
    public function create(): View
    { 
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Insert new encryption key after every login attempt
        $this->createEncryptionKey();

        return redirect()->intended(route('dashboard',absolute: false));
    }

    /**
     * Create new key for a user for message encryption
     */
    private function createEncryptionKey(){

        $user = Auth::user();
        $encryptionKey = $this->generateEncryptionKey($user->id);
        
        UserKey::create([
            'sender_id' => $user->id,
            'encrypted_key' => $encryptionKey,
        ]);

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

}
