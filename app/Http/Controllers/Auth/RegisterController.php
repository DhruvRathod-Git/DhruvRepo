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

class RegisterController extends Controller
{

    public function register()
    {
        return view('auth.register');
    }

    public function homepage()
{
    $totalEmployees = Employees::count();
    $totalLeaves = Leave::count();
    $totalSalaryPaid = Salary::sum('net_salary');

    $monthlyAttendance = Attendance::selectRaw('COUNT(*) as total, MONTH(date) as month')
        ->groupBy('month')->get();

    return view('employee.homepage', compact('totalEmployees', 'totalLeaves', 'totalSalaryPaid', 
        'monthlyAttendance'
    ));
}

public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255','email'],
        'password' => ['required', 'min:4'],
    ]);
    
    $Employees = Employees::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
      ]);
      return redirect()->route('auth.login')->with('success', 'Registration successful!');
    }
}