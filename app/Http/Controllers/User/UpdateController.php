<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

# Models
use App\User;
use App\Models\User\Gender;

class UpdateController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find($request->id)->load(['profile']);
        
        return view('contents.user.update.index', [
            'user' => $user,
            'genders' => $this->getGenders()
        ]);
    }

    public function update(Request $request)
    {
        try {
            if (User::where('id', '!=', $request->id)->where('email', $request->email)->count()) return redirect()->back()->withErrors([
                'email' => 'Email sudah digunakan!'
            ])->withInput();

            if ($request->password != $request->password_confirm) return redirect()->back()->withErrors([
                'password' => 'Password dan Password (ulangi) harus sama!'
            ])->withInput(); 
        } catch (\Exception $e) {
            return redirect()->route('user.create')->withErrors([
                'validation' => 'Terjadi kesalahan saat validasi!'
            ]);
        }

        try {
            $user = User::find($request->id)->load(['profile']);
            $password = $user->password;
            
            $user->fill($request->all());
            $user->password = empty($request->password) ? $password : Hash::make($request->password);
            $user->save();

            $user->attachRole($request->role_id);
            
            $profile = $user->profile;
            $profile->fill($request->all());
            $profile->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
            $profile->fullname = $user->name;
            $profile->user_id = $user->id;
            $profile->save();

            return redirect()->route('user.list')->with('success', 'Pengguna berhasil diubah!');
        } catch (\Exception $e) {
            return redirect()->route('user.list')->withErrors([
                'save' => 'Terjadi kesalahan saat menyimpan perubahan data pengguna!'
            ]);
        }

        return $request->all();
    }

    private function getGenders()
    {
        return Gender::all();
    }
}
