<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationMail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Subscription;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'required|string|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'company_phone' => 'required|string|max:15',
            'company_email' => 'required|string|email|max:255',
            'company_address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'user_count' => 'required|integer|min:1',
            'package_type_id' => 'required|exists:package_types,id',
            'agreement' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }
        try {
            DB::beginTransaction();
            $companyCode = $this->generateCompanyCode();
            $company = Company::create([
                'name' => $request->company_name,
                'phone' => $request->company_phone,
                'email' => $request->company_email,
                'address' => $request->company_address,
                'postal_code' => $request->postal_code,
                'user_count' => $request->user_count,
                'company_code' => $companyCode,
                'package_type_id' => $request->package_type_id,
            ]);

            $user = User::create([
                'name' => $request->name,
                'user_id' => $request->user_id,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'company_id' => $company->id,
            ]);

            $subscription = Subscription::create([
                'company_id' => $company->id,
                'package_type_id' => $request->package_type_id,
                // 'start_date' => now(),
                // 'end_date' => now()->addYear(),
                // 'status' => 'active',
            ]);
            DB::commit();
            // Send registration email
            $credentials = [
                'user_id' => $request->user_id,
                'email' => $request->email,
                'password' => $request->password // Consider sending a password reset link instead for security
            ];
            Mail::to($user->email)->send(new RegistrationMail($user, $company, $credentials));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }



        return response()->json(['message' => 'Registration successful'], 201);
    }
    private function generateCompanyCode()
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $alphanumeric = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $firstChar = $alphabet[random_int(0, strlen($alphabet) - 1)];
        $secondChar = $alphanumeric[random_int(0, strlen($alphanumeric) - 1)];
        $thirdChar = $alphanumeric[random_int(0, strlen($alphanumeric) - 1)];

        return $firstChar . $secondChar . $thirdChar;
    }
}