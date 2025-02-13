<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        // echo bcrypt('Dhananjay@123');exit;
        return view('admin/login');
    }

    // Handle the login request
    public function login(Request $request)
    {
        // Validate the form data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Redirect to intended page if login is successful
            return redirect()->intended('/admin/dashboard');
        }

        // If authentication fails, redirect back to the login page with an error
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout(); // This will log the user out

        // Optionally, you can invalidate the session to clear any lingering data
        session()->invalidate();
        session()->regenerateToken();

        // Redirect the user to a specific route after logout (e.g., the homepage or login page)
        return redirect()->route('admin.login'); // You can change the route as needed
    }
}
