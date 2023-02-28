<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use App\Utility\StringUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function initiateEnrollment(Request $request){
        //todo validation
        $validator = Validator::make($request -> all(),[
            'name' => 'required|string',
            'email' => 'required|string|email'
        ]);
        if ($validator -> fails()){
            return response()->json([
                'responseCode' => '106',
                'responseMessage' => StringUtility::formatValidatorMessage($validator->messages()->toJson())
            ]);
        }
        $user = User::where('email','=',$request->email)->first();
        if ($user){
            if ($user->status == 'ACTIVE') {
                return response()->json([
                    'responseCode' => '26',
                    'responseMessage' => "Email Already Exist"
                ]);
            }
            $user->delete();
        }
        $otp = rand(1000,9999);
        $otpHash = Hash::make($otp);
        //Hash::check('plain-text', $hashedPassword)
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'otp' => $otpHash
        ]);
        //Mail::to($request->email)->send((new OtpMail( [ 'otp' => $otp] ))->subject('Jacks Snuff'));
        Mail::to($request->email)->send((new OtpMail( [ 'otp' => $otp] )));
        return response()->json([
            'responseCode' => '00',
            'responseMessage' => "OTP has been sent to your email. please supply, to complete registration "
        ]);
    }

    public function completeEnrollment(Request $request){
        //todo validation
        $validator = Validator::make($request -> all(),[
            'email' => 'required|string',
            'otp' => 'required|string|max:4',
            'password' => 'required|string',
            'passwordConfirmation' => 'required|string'
        ]);
        if ($validator -> fails()){
            return response()->json([
                'responseCode' => '106',
                'responseMessage' => StringUtility::formatValidatorMessage($validator->messages()->toJson())
            ]);
        }
        if ($request->password != $request->passwordConfirmation){
            return response()->json([
                'responseCode' => '106',
                'responseMessage' => "Password Mismatched"
            ]);
        }
        $user = User::where('email','=',$request->email)->where('email','=',$request->email)->first();
        if ($user){
            if ($user->status == 'ACTIVE') {
                return response()->json([
                    'responseCode' => '26',
                    'responseMessage' => "Email Already Exist"
                ]);
            }
        }else{
            return response()->json([
                'responseCode' => '12',
                'responseMessage' => "Initiate Enrollment is require"
            ]);
        }
        if (!Hash::check($request->otp, $user->otp)){
            return response()->json([
                'responseCode' => '26',
                'responseMessage' => "Invalid OTP "
            ]);
        }
        $hashedPassword = Hash::make($request->password);
        //Hash::check('plain-text', $hashedPassword)
        $user->update([
            'otp' => '',
            'status' => 'ACTIVE',
            'password' => $hashedPassword,
        ]);
        return response()->json([
            'responseCode' => '00',
            'responseMessage' => "Success"
        ]);
    }

    public function login(Request $request)
    {
        //todo validation
        $validator = Validator::make($request -> all(),[
            'password' => 'required|string',
            'email' => 'required|string|email'
        ]);
        if ($validator -> fails()){
            return response()->json([
                'responseCode' => '106',
                'responseMessage' => StringUtility::formatValidatorMessage($validator->messages()->toJson())
            ]);
        }
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'responseCode' => '12',
                'responseMessage' => "Invalid Credentials."
            ]);
        }
        $user = Auth::user();
        if ($user->status == 'PENDING') {
            return response()->json([
                'responseCode' => '01',
                'responseMessage' => "Enrollment not completed."
            ]);
        }
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24); // 1 day
        return response([
            'responseCode' => '00',
            'responseMessage' => "Success",
            'token' => $token,
            'user' => $user
        ])->withCookie($cookie);
    }

    public function userDetails()
    {
        return response([
            'responseCode' => '00',
            'responseMessage' => 'Success',
            'user' => Auth::user()
        ]);
    }

    public function initiatePasswordReset(Request $request)
    {
        //todo validation
        $validator = Validator::make($request -> all(),[
            'email' => 'required|string|email'
        ]);
        if ($validator -> fails()){
            return response()->json([
                'responseCode' => '106',
                'responseMessage' => StringUtility::formatValidatorMessage($validator->messages()->toJson())
            ]);
        }
        $user = User::where('email','=',$request->email)->where('email','=',$request->email)->first();
        if (!$user){
            return response()->json([
                'responseCode' => '12',
                'responseMessage' => "Email not found!"
            ]);
        }
        $otp = rand(1000,9999);
        $otpHash = Hash::make($otp);
        $user->update([ 'otp' => $otpHash ]);
        Mail::to($request->email)->send((new OtpMail( [ 'otp' => $otp] )));
        return response([
            'responseCode' => '00',
            'responseMessage' => "OTP has been sent to your email. please supply, to complete password reset"
        ]);
    }

    public function completePasswordReset(Request $request)
    {
        //todo validation
        $validator = Validator::make($request -> all(),[
            'email' => 'required|string',
            'otp' => 'required|string|max:4',
            'password' => 'required|string',
            'passwordConfirmation' => 'required|string'
        ]);
        if ($validator -> fails()){
            return response()->json([
                'responseCode' => '106',
                'responseMessage' => StringUtility::formatValidatorMessage($validator->messages()->toJson())
            ]);
        }
        if ($request->password != $request->passwordConfirmation){
            return response()->json([
                'responseCode' => '106',
                'responseMessage' => "Password Mismatched"
            ]);
        }
        $user = User::where('email','=',$request->email)->where('email','=',$request->email)->first();
        if (!$user){
            return response()->json([
                'responseCode' => '12',
                'responseMessage' => "Email not found!"
            ]);
        }
        if (!Hash::check($request->otp, $user->otp)){
            return response()->json([
                'responseCode' => '26',
                'responseMessage' => "Invalid OTP "
            ]);
        }
        $hashedPassword = Hash::make($request->password);
        //Hash::check('plain-text', $hashedPassword)
        $user->update([
            'otp' => '',
            'status' => 'ACTIVE',
            'password' => $hashedPassword,
        ]);
        return response()->json([
            'responseCode' => '00',
            'responseMessage' => "Success"
        ]);
    }

    public function resendOtp(Request $request)
    {
        //todo validation
        $validator = Validator::make($request -> all(),[
            'email' => 'required|string|email'
        ]);
        if ($validator -> fails()){
            return response()->json([
                'responseCode' => '106',
                'responseMessage' => StringUtility::formatValidatorMessage($validator->messages()->toJson())
            ]);
        }
        $user = User::where('email','=',$request->email)->where('email','=',$request->email)->first();
        if (!$user){
            return response()->json([
                'responseCode' => '12',
                'responseMessage' => "Email not found!"
            ]);
        }
        $otp = rand(1000,9999);
        $otpHash = Hash::make($otp);
        $user->update([ 'otp' => $otpHash ]);
        Mail::to($request->email)->send((new OtpMail( [ 'otp' => $otp] )));
        return response([
            'responseCode' => '00',
            'responseMessage' => "OTP has been sent to your email. please supply, to complete your action"
        ]);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'responseCode' => '00',
            'responseMessage' => 'Success'
        ])->withCookie($cookie);
    }

    public function expiredSession()
    {
        return response([
            'responseCode' => '115',
            'responseMessage' => 'Invalid or Expired Session'
        ]);
    }

}
