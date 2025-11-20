<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Leave;
use App\Models\Employees;
use Carbon\Carbon;
use App\Exports\SalaryExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employees::all();

        if ($request->ajax()) {
            $query = Salary::with('employee');

            if (Auth::user()->role === 'employee') {
                $query->where('employee_id', Auth::id());
            }

            return DataTables::of($query)
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" 
                                class="btn btn-info btn-sm mb-1 show-salary"
                                data-id="'.$row->id.'" data-url="'.route('salary.show', $row->id).'">
                                <i class="bi bi-eye-fill"></i> Show
                            </a> ';

                             $btn .= '<a href="'.route('salary.download', $row->employee_id).'"
                                 class="btn btn-warning btn-sm mb-1">
                                 <i class="bi bi-file-earmark-pdf"></i> PDF
                             </a> <br>';

                    if (Auth::user()->role === 'admin') {

                        $btn .= '<a href="javascript:void(0)" 
                                    class="btn btn-primary  btn-sm edit-salary"
                                    data-id="'.$row->id.'" data-url="'.route('salary.edit', $row->id).'">
                                    <i class="bi bi-pencil-square"></i> Edit
                                 </a> ';

                        $btn .= '<a href="javascript:void(0)" 
                                    class="btn btn-danger btn-sm delete-salary"
                                    data-id="'.$row->id.'" data-url="'.route('salary.destroy', $row->id).'">
                                    <i class="bi bi-trash3"></i> Delete
                                 </a>';

                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('salary.index', compact('employee'));
    }

    public function show($id)
    {
        $salary = Salary::with('employee')->findOrFail($id);
        return response()->json($salary);
    }

    public function generateSalary(Request $request)
    {
        $employee_id = $request->employee_id;
        $month = $request->month;

        $employee = Employees::findOrFail($employee_id);
        $salary = $employee->salary;

        // PF is 12% of salary
        $pf = round($salary * 0.12, 2);

        $leaveCount = Leave::where('employee', $employee_id)
            ->where('leave_date', Carbon::parse($month)->month)
            ->count();

        // Calculate leave deduction based on per day salary
        // Assuming 30 days in a month
        $perDaySalary = $salary / 30;
        $leaveDeduction = round($leaveCount * $perDaySalary, 2);

        // Net salary
        $net_salary = round($salary - ($pf + $leaveDeduction), 2);

        return response()->json([
            'salary' => $salary,
            'pf' => $pf,
            'leave_deduction' => $leaveDeduction,
            'net_salary' => $net_salary
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'salary' => 'required|numeric',
            'pf' => 'required|numeric',
            'leave_deduction' => 'required|numeric',
            'month' => 'required'
        ]);

        $netSalary = $request->salary - ($request->pf + $request->leave_deduction);

        $data = Salary::create([
            'employee_id' => $request->employee_id,
            'salary' => $request->salary,
            'pf' => $request->pf,
            'leave_deduction' => $request->leave_deduction,
            'net_salary' => $netSalary,
            'month' => $request->month
        ]);

        return response()->json($data);
    }

    public function edit($id)
    {
        $salary = Salary::findOrFail($id);
        return response()->json($salary);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required',
            'salary' => 'required|numeric',
            'pf' => 'required|numeric',
            'leave_deduction' => 'required|numeric',
            'month' => 'required'
        ]);

        $salary = Salary::findOrFail($id);
        $netSalary = $request->salary - ($request->pf + $request->leave_deduction);

        $salary->update([
            'employee_id' => $request->employee_id,
            'salary' => $request->salary,
            'pf' => $request->pf,
            'leave_deduction' => $request->leave_deduction,
            'net_salary' => $netSalary,
            'month' => $request->month
        ]);

        return response()->json($salary);
    }

    public function destroy($id)
    {
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return response()->json(['success' => true]);
    }

    public function export()
    {
        return Excel::download(new SalaryExport(), 'Salary-Data.xlsx');
    }

    public function downloadSalaryPDF($employee_id)
{
    $salary = Salary::where('employee_id', $employee_id)->firstOrFail();
    $employee = Employees::findOrFail($employee_id);

    $data = [
        'name' => $employee->name,
        'email' => $employee->email,
        'phone' => $employee->phone,
        'address' => $employee->address,
        'image' => public_path($employee->image),
        'month' => $salary->month,
        'salary' => $salary->salary,
        'pf' => $salary->pf,
        'leave_deduction' => $salary->leave_deduction,
        'net_salary' => $salary->net_salary,
        'image' => public_path('vnnovate.png')
    ];

    $pdf = PDF::loadView('salary.pdf', compact('data'))
        ->setPaper('A4', 'portrait');

    return $pdf->download('salary-slip-' . $employee_id . '.pdf');
}

}
