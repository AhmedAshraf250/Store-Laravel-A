<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('front.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function user2Fa(Request $request): View
    {
        return view('front.profile.2fa', [
            'user' => $request->user(),
        ]);
    }
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update user basic info
        $user->fill($request->validated());

        // If email changed, reset verification
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'first_name',
                'last_name',
                'birthday',
                'gender',
                'street_address',
                'city',
                'state',
                'postal_code',
                'country',
                'locale',
            ])
        );

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Update the user's settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email_orders' => 'boolean',
            'email_promotions' => 'boolean',
            'email_newsletter' => 'boolean',
        ]);

        $request->user()->update([
            'email_orders' => $request->has('email_orders'),
            'email_promotions' => $request->has('email_promotions'),
            'email_newsletter' => $request->has('email_newsletter'),
        ]);

        // Update profile locale & country
        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->only(['locale', 'country'])
        );

        return back()->with('status', 'settings-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
