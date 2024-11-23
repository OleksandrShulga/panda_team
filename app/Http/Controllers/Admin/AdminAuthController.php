<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class AdminAuthController extends Controller
{
    // Показати форму реєстрації
    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:admin_users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.register')
                ->withErrors($validator)
                ->withInput();
        }

        $adminUser = AdminUser::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        AdminUser::addRoles($adminUser->id);

        $email = $request->email;

        Mail::raw('Для підтвердження своєї пошти перейдіть за посиланням ' . url('/admin/confirm/' . $adminUser->id), function ($message) use ($email){
            $message->to($email)
                ->subject('Підтвердження акаунту');
        });

        return redirect('/admin/login')->with('success', 'Registration Successfull');
    }

    public function confirm(Request $request) {
        if (AdminUser::find($request->id)) {
            AdminUser::where('id', $request->id)->update(['activated' => true]);
            return redirect('/admin/login')->with('status', 'Активація пошти відбулася успішно');
        }
        return redirect('/admin/login')->with('status', 'З активацією пошти виникли складнощі');
    }
}
