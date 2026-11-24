<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required','string'],
            'password' => ['required','string'],
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'matricule';
        if (Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('catalogue'));
        }
        return back()->withErrors(['login' => 'Identifiants incorrects. Vérifiez votre matricule/e-mail et mot de passe.'])->onlyInput('login');
    }

    public function showRegister()
    {
        return view('auth.register', ['filieres' => Filiere::orderBy('name')->get()]);
    }

    public function register(Request $request)
    {
        $kind = $request->input('kind', 'etudiant');
        $rules = [
            'name' => ['required','string','max:255'],
            'kind' => ['required','in:etudiant,personnel'],
            'filiere_id' => ['required','exists:filieres,id'],
            'password' => ['required','confirmed', Password::min(8)],
        ];
        if ($kind === 'etudiant') {
            $rules['matricule'] = ['required','regex:/^ENEAM-\d{4}-\d{4}$/','unique:users,matricule'];
            $rules['email'] = ['required','email','unique:users,email'];
        } else {
            $rules['email'] = ['required','email','regex:/@eneam\.uac\.bj$/','unique:users,email'];
        }
        $data = $request->validate($rules);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'matricule' => $data['matricule'] ?? null,
            'role' => $kind === 'personnel' ? 'encadreur' : 'etudiant',
            'filiere_id' => $data['filiere_id'],
            'password' => Hash::make($data['password']),
        ]);
        Auth::login($user);
        return redirect()->route('catalogue')->with('toast', 'Compte créé avec succès.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing');
    }
}
