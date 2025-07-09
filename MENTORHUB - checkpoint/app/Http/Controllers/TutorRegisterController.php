<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutor;
use Illuminate\Support\Facades\Validator;

class TutorRegisterController extends Controller
{
    public function tutorRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tutors',
            'password' => 'required|string|confirmed|min:8',
            'tutor_id' => 'required|string|unique:tutors',
            'specialization' => 'required|string|max:1000',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'rate' => 'required|numeric|min:0',
            'terms' => 'accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tutor = Tutor::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'tutor_id' => $request->input('tutor_id'),
            'specialization' => $request->input('specialization'),
            'phone' => $request->input('phone'),
            'bio' => $request->input('bio'),
            'session_rate' => $request->input('rate'),
        ]);

        return redirect()->route('tutor.signup')->with('tutor_success', 'Tutor registration successful! You can now log in with your credentials.');
    }
}
