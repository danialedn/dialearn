<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    /**
     * Show the game rules page or redirect authenticated users
     */
    public function showGameRules(): Response|\Illuminate\Http\RedirectResponse
    {
        // If user is already authenticated, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        // صفحه قوانین بازی را نمایش می‌دهیم که دکمه‌های ورود و ثبت‌نام دارد
        return inertia('GameRules');
    }
    
    /**
     * Show login page for existing users
     */
    public function showLogin(): Response|\Illuminate\Http\RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return Inertia::render('Login');
    }
    
    /**
     * Handle login for existing users
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'نام کاربری یا رمز عبور اشتباه است.',
            'password' => 'نام کاربری یا رمز عبور اشتباه است.',
        ])->withInput($request->except('password'));
    }
    
    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('game.rules');
    }

    /**
     * Accept game rules and redirect to demographic form
     */
    public function acceptRules(Request $request)
    {
        // Store rules acceptance in session
        session(['rules_accepted' => true]);

        return redirect()->route('demographic.form');
    }

    /**
     * Show the demographic information form
     */
    public function showDemographicForm()
    {
        // Check if rules were accepted
        if (!session('rules_accepted')) {
            return redirect()->route('game.rules');
        }

        return Inertia::render('DemographicForm');
    }

    /**
     * Complete user profile with demographic information
     */
    public function completeProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:10|max:100',
            'education_level' => 'required|in:elementary,middle_school,high_school,diploma,bachelor,master,phd',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@dialearn.local', // Temporary email
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'age' => $request->age,
            'education_level' => $request->education_level,
            'profile_picture' => $profilePicturePath,
            'rules_accepted' => true,
            'rules_accepted_at' => now()
        ]);

        // Log the user in
        Auth::login($user);

        // Clear session
        session()->forget('rules_accepted');

        return redirect()->route('dashboard');
    }

    /**
     * Show the main dashboard
     */
    public function dashboard(): Response
    {
        $user = Auth::user();
        
        // Get user progress
        $userProgress = $user->userProgress()->with('stage')->get();
        
        // Get total stages count
        $totalStages = \App\Models\Stage::count();

        return Inertia::render('Dashboard', [
            'user' => $user,
            'userProgress' => $userProgress,
            'totalStages' => $totalStages
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:10|max:100',
            'education_level' => 'required|in:elementary,middle_school,high_school,diploma,bachelor,master,phd',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'username', 'email', 'gender', 'age', 'education_level']);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $data['profile_picture'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'پروفایل شما با موفقیت به‌روزرسانی شد!');
    }
}
