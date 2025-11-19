<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\Leave;
use App\Models\Salary;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function homepage()
{
    $totalEmployees = Employees::count();
    $totalLeaves = Leave::count();
    $totalSalaryPaid = Salary::sum('net_salary');

    $monthlyAttendance = Attendance::selectRaw('COUNT(*) as total, MONTH(date) as month')
        ->groupBy('month')->get();

    return view('auth.homepage', compact('totalEmployees', 'totalLeaves', 'totalSalaryPaid', 
        'monthlyAttendance'
    ));
}
    protected function create(array $data): Employees
    {
        return Employees::create([
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function stores(Request $request): RedirectResponse
{
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if (Auth::user()->role === 'employee') {
            return redirect()->route('employee.homepage');

        } elseif (Auth::user()->role === 'admin') {
            return redirect()->route('auth.homepage');
        }
        
        return redirect()->route('auth.homepage');
    }
    return back()->withErrors(['email' => 'Invalid credentials']);
}
    
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('auth.login')->with('success', 'You have been logged out');
    }   
}