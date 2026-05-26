<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\AadharOtpMail;

class AadharVerificationController extends Controller
{
    /**
     * Send simulated Aadhaar e-KYC OTP after strict validation.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'aadhar_number' => 'required|string|size:12',
        ]);

        $aadharNumber = $request->input('aadhar_number');
        $user = Auth::user();

        
        \Log::info('Aadhaar verification request received', [
            'input' => $aadharNumber,
            'input_len' => strlen($aadharNumber),
        ]);

        $registry = DB::table('aadhaar_registries')
            ->where('aadhar_number', $aadharNumber)
            ->first();

        \Log::info('Aadhaar registry query result', [
            'found' => !empty($registry),
            'registry' => $registry,
        ]);

        if (!$registry) {
            return response()->json([
                'success'    => false,
                'error_type' => 'not_found',
                'message'    => 'Aadhaar Number not found in UIDAI Registry. Please enter a valid registered Aadhaar.'
            ]);
        }

        
        $profileName  = strtolower(trim(preg_replace('/\s+/', ' ', $user->name)));
        $registryName = strtolower(trim(preg_replace('/\s+/', ' ', $registry->registered_name)));

        if ($profileName !== $registryName) {
            return response()->json([
                'success'    => false,
                'error_type' => 'name_mismatch',
                'message'    => 'e-KYC Failed: Identity mismatch. The name on your profile ("' . $user->name . '") does not match the official Aadhaar registry record ("' . $registry->registered_name . '") for this number.'
            ]);
        }

        // 3. Generate secure OTP
        $otp = mt_rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        
        Session::put('aadhar_otp', $otp);
        Session::put('aadhar_otp_number', $aadharNumber);
        Session::put('aadhar_otp_expires_at', $expiresAt);

        
        $mobile = $registry->registered_mobile;
        $maskedMobile = str_repeat('X', strlen($mobile) - 4) . substr($mobile, -4);

        try {
            Mail::to($user->email)->send(new AadharOtpMail((string)$otp, $user->name));
        } catch (\Exception $e) {
            \Log::warning('Aadhaar OTP Email not sent: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'mobile'  => $maskedMobile,
            'message' => 'Aadhaar e-KYC OTP has been sent to your registered email address.'
        ]);
    }

    /**
     * Verify Aadhaar OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp'           => 'required|string|size:6',
            'aadhar_number' => 'required|string|size:12',
        ]);

        $otp          = $request->input('otp');
        $aadharNumber = $request->input('aadhar_number');

        $sessionOtp     = Session::get('aadhar_otp');
        $sessionNumber  = Session::get('aadhar_otp_number');
        $sessionExpires = Session::get('aadhar_otp_expires_at');

        if (!$sessionOtp || !$sessionNumber || !$sessionExpires) {
            return response()->json([
                'success' => false,
                'message' => 'OTP request expired or not found. Please click Resend OTP.'
            ]);
        }

        if (now()->gt($sessionExpires)) {
            Session::forget(['aadhar_otp', 'aadhar_otp_number', 'aadhar_otp_expires_at']);
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new OTP code.'
            ]);
        }

        if ($otp !== (string)$sessionOtp || $aadharNumber !== $sessionNumber) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please check the code and try again.'
            ]);
        }

        
        Session::put('aadhar_verified_number', $aadharNumber);

        // Clean up temporary OTP session keys
        Session::forget(['aadhar_otp', 'aadhar_otp_number', 'aadhar_otp_expires_at']);

        return response()->json([
            'success' => true,
            'message' => 'Aadhaar e-KYC Verification Successful!'
        ]);
    }
}
