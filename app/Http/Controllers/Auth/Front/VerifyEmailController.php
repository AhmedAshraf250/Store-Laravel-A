<?php

namespace App\Http\Controllers\Auth\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Verify email using OTP code
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'الرجاء إدخال كود التفعيل',
            'otp.digits' => 'كود التفعيل يجب أن يكون 6 أرقام',
        ]);

        $user = Auth::user('web');

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Your email is already verified.');
        }

        // التحقق من صحة OTP
        if (!$user->isOtpValid($request->otp)) {
            return back()->with('error', 'Invalid or expired OTP code. Please try again.');
        }

        // تفعيل البريد
        $user->markEmailAsVerified();
        $user->clearOtp();

        return redirect()->route('profile.edit')->with('verified', true)  // ← Special flag for email verification
            ->with('success', 'تم تفعيل بريدك الإلكتروني بنجاح!');
    }
}
