<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\EmployeeController;
use App\Exports\EmployeeExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function dashboard()
{
    $totalEmployees = Employees::count();
    return view('auth.dashboard', compact('totalEmployees'));
}

    public function index(Request $request)
{
    if ($request->ajax()) {

        $data = Employees::select('*');

        return DataTables::of($data)
            ->addColumn('image', function ($row) {
                return "<img src='/storage/{$row->image}' width='60' height='50'>";
            })
            ->addColumn('documents', function ($row) {
                $docs = json_decode($row->documents, true) ?? [];
                return implode("<br>", array_map(function ($d) {
                    return "<a href='/storage/$d' target='_blank'>$d</a>";
                }, $docs));
            })
            ->addColumn('experiences', function ($row) {
                $exps = json_decode($row->experiences, true) ?? [];
                return implode("<br>", array_map(function ($e) {
                    return "{$e['company']} - {$e['role']} (Year:{$e['years']})";
                }, $exps));
            })
            ->addColumn('action', function ($row) {
               $btn = '<a href="javascript:void(0)" 
                                class="btn btn-info btn-lg show-emp"
                                data-id="'.$row->id.'" data-url="'.route('emp.show', $row->id).'">
                                <i class="bi bi-eye-fill"></i>
                            </a> ';

                    if (Auth::user()->role === 'admin') {

                        $btn .= '<a href="javascript:void(0)" 
                                    class="btn btn-primary btn-lg edit-emp mt-1"
                                    data-id="'.$row->id.'" data-url="'.route('emp.edit', $row->id).'">
                                    <i class="bi bi-pencil-square"></i>
                                 </a> ';

                        $btn .= '<a href="javascript:void(0)" 
                                    class="btn btn-danger delete-emp mt-1 btn-lg"
                                    data-id="'.$row->id.'" data-url="'.route('emp.destroy', $row->id).'">
                                    <i class="bi bi-trash3"></i>
                                 </a>';
                    }

                    return $btn;
            })
            ->rawColumns(['image','documents','experiences','action'])
            ->make(true);
    }
    return view('emp.index');
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'name'      => 'required|string',
        'email'     => 'required|email',
        'password'  => 'required|min:4',
        'phone'     => 'required|min:10',
        'address'   => 'required|string',
        'image'     => 'nullable|image|mimes:jpg,jpeg,png',
        'documents.*' => 'nullable|mimes:pdf,doc,docx,jpg,png,jpeg',
        'experience' => 'nullable|array'
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('uploads', 'public');
    }

    $docs = [];
    if ($request->hasFile('documents')) {
        foreach ($request->file('documents') as $file) {
            $docs[] = $file->store('employees/documents', 'public');
        }
    }

    $employee = Employees::create([
        'name'        => $validated['name'],
        'email'       => $validated['email'],
        'password'    => Hash::make($validated['password']),
        'phone'       => $validated['phone'],
        'address'     => $validated['address'],
        'image'       => $imagePath, 
        'documents'   => json_encode($docs),
        'experiences' => json_encode($request->experience),
    ]);

    return response()->json($employee);
}

    public function show($id)
    {
        $employee = Employees::findOrFail($id);
        return response()->json($employee);
    }

    public function edit($id)
    {
        $employee = Employees::findOrFail($id);

        $employee->experiences = json_decode($employee->experiences, true);
        $employee->documents   = json_decode($employee->documents, true);

        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        $employee = Employees::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:employees,email,' . $id,
            'phone'     => 'required|min:10',
            'address'   => 'required|string',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png',
            'documents.*' => 'nullable|mimes:pdf,doc,docx,jpg,png,jpeg',
            'experience' => 'nullable|array',
            'experience.*.company' => 'nullable|string',
            'experience.*.role'    => 'nullable|string',
            'experience.*.years'   => 'nullable|numeric',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('employees/images', 'public');
        }

        if ($request->hasFile('documents')) {
            $docs = [];
            foreach ($request->file('documents') as $file) {
                $docs[] = $file->store('employees/documents', 'public');
            }
            $validated['documents'] = json_encode($docs);
        }

        $validated['experiences'] = json_encode($request->experience);

        $employee->update($validated);

        if ($request->password) {
            $employee->password = Hash::make($request->password);
            $employee->save();
        }

        return response()->json($employee);
    }

    public function destroy($id)
    {
        $Employees = Employees::findOrFail($id);
        $Employees->delete();

        return response()->json(['success' => true, 'message' => 'Data deleted successfully!']);
    }

    // public function export()
    // {
    //     return Excel::download(new EmployeeExport, 'Employee-Data.xlsx');
    // }
}
