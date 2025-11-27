<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('dashboard.profile.edit', ['user' => $user, 'countries' => Countries::getNames(), 'locales' => Languages::getNames()]);
    }

    public function update(Request $request)
    {
        /**
         * To get the authenticated user we can use:
         *   1. $request->user() // from the request
         *   2. Auth::user()     // Auth Facade 
         */
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birthday' => ['nullable', 'date', 'before:today'],
            'gender' => ['in:male,female'],

            'street_address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],

            'country' => ['required', 'string', 'size:2'],
            'locale' => ['nullable', 'string', 'size:2'],
        ]);

        $user = $request->user();
        $user->profile->fill($request->all())->save();

        // $profile = $user->profile;   // profile is (one to one relation) // return profile model object
        // if ($profile->first_name) {  // $profile only always returns true because of the withDefault() method, so added 'user_id' to make check
        //     $profile->update($request->all());
        // } else {
        //     // $request->merge(['user_id' => $user->id]);
        //     // Profile::create($request->all());

        //     //    [OR]  with Relation can do it with same result
        //     // request does not contain 'user_id', so added it manually from relation to create new profile
        //     // Relation can used to create a new record by passing the attributes to the create method
        //     $user->profile()->create($request->all());
        // }

        return redirect()->route('dashboard.profile.edit')->with('success', 'Profile Updated!');
    }
}
