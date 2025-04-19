<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

class AuthController extends Controller
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->response(Response::HTTP_BAD_REQUEST, 'Validation Error', $validator->errors());
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ]);

            $this->logger->info('User registered successfully: ' . $user->email);
            return $this->response(Response::HTTP_CREATED, 'User registered successfully', [
                'user' => $user,
                'token' => $user->remember_token,
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Error registering user: ' . $e->getMessage());
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, 'Registration failed: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->response(Response::HTTP_BAD_REQUEST, 'Validation Error', $validator->errors());
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Regenerate remember token on each login
            $user->remember_token = Str::random(60);
            $user->save();

            $this->logger->info('User logged in successfully: ' . $user->email);
            return $this->response(Response::HTTP_OK, 'Login successful', [
                'user' => $user,
                'token' => $user->remember_token,
            ]);
        } else {
            $this->logger->warning('Login failed for email: ' . $request->email);
            return $this->response(Response::HTTP_UNAUTHORIZED, 'Invalid credentials');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->remember_token = null; // Clear remember token
            $user->save();
            Auth::logout();
            $this->logger->info('User logged out successfully: ' . $user->email);
            return $this->response(Response::HTTP_OK, 'Logged out successfully');
        } else {
            $this->logger->warning('Logout failed: No user authenticated.');
            return $this->response(Response::HTTP_BAD_REQUEST, 'No user authenticated.'); // Or 400
        }
    }
}
