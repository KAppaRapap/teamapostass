<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'city' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
        
        // Atualiza os dados básicos
        $user->update($validated);

        // Upload da foto de perfil
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
            $user->save();
        }
        
        return redirect()->route('profile.edit')
            ->with('success', 'Perfil atualizado com sucesso.');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('settings.index')
            ->with('success', 'Senha atualizada com sucesso.');
    }

    /**
     * Update the user's notification preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateNotificationPreferences(Request $request)
    {
        $user = Auth::user();
        
        $preferences = $request->only([
            'notify_new_draws',
            'notify_results',
            'notify_group_activities',
            'email_notifications',
        ]);
        
        // Aqui você pode armazenar as preferências em uma tabela separada
        // ou em um campo JSON no modelo de usuário
        
        return redirect()->route('settings.index')
            ->with('success', 'Preferências de notificação atualizadas com sucesso.');
    }
}
