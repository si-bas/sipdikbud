<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

# Models
use App\User;
use App\Models\User\User_profile;

class RegisterController extends Controller
{
    public function index()
    {
        return view('contents.home.register.index', [
            'genders' => app(\App\Http\Controllers\User\CreateController::class)->getGenders()
        ]);
    }

    public function create(Request $request)
    {
        try {
            if (User::where('email', $request->email)->count()) return redirect()->back()->withErrors([
                'email' => 'Email sudah digunakan!'
            ])->withInput();
 
            if (empty($request->gender_id)) return redirect()->back()->withErrors([
                    'gender' => 'Jenis kelamin harus diisi!'
            ])->withInput(); 
        } catch (\Exception $e) {
            return redirect()->route('home.register')->withErrors([
                'validation' => $e->getMessage()
            ]);
        }

        try {
            $user = new User($request->all());
            $user->password = Hash::make($request->password);
            $user->save();

            $user->attachRole('researcher');

            $profile = new User_profile($request->all());
            $profile->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
            $profile->fullname = $user->name;
            $profile->user_id = $user->id;
            $profile->save();

            Auth::login($user);
            return redirect()->route('login');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'validation' => $e->getMessage()
            ]);
        }
    }
}
