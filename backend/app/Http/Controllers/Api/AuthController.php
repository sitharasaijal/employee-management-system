<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $employee = Employee::where('email', $credentials['email'])->first();

        if (! $employee || ! $employee->password
            || ! Hash::check($credentials['password'], $employee->password)) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        if (! $employee->canLogin()) {
            throw ValidationException::withMessages([
                'email' => 'Your account is not allowed to log in. Please contact your administrator.',
            ]);
        }

        $employee->forceFill(['last_login_at' => now()])->save();

        $token = $employee->createToken('react-app')->plainTextToken;

        return response()->json([
            'token'    => $token,
            'employee' => $this->profile($employee),
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($this->profile($request->user()));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    private function profile(Employee $employee): array
    {
        return [
            'id'            => $employee->id,
            'employee_code' => $employee->employee_code,
            'name'          => trim("{$employee->first_name} {$employee->last_name}"),
            'email'         => $employee->email,
            'roles'         => $employee->getRoleNames(),
            'permissions'   => $employee->getAllPermissions()->pluck('name'),
        ];
    }
}