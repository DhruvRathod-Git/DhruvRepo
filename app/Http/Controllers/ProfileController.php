<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Employees;

class ProfileController extends Controller
{
    public function show($id)
    {
        $Employees = Employees::findOrFail($id);
        return view('profile.show', compact('Employees'));
    }

    public function edit($id)
    {
        $Employees = Employees::findOrFail($id);
        return view('profile.edit', compact('Employees'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'image'   => 'nullable|image|mimes:jpg,jpeg,png',
            'name'    => 'required|string|max:255',
            'phone'   => 'required|digits:10',
            'address' => 'required|string|max:255',
        ]);

        $Employees = Employees::findOrFail($id);
        $imagePath = $Employees->image;

        if ($request->hasFile('image')) {
            if ($Employees->image && Storage::exists('public/' . $Employees->image)) {
                Storage::delete('public/' . $Employees->image);
            }
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        $Employees->name    = $validated['name'];
        $Employees->phone   = $validated['phone'];
        $Employees->address = $validated['address'];
        $Employees->image   = $imagePath;

        $Employees->save();

        return redirect()->route('profile.show', $Employees->id)->with('success', 'Profile updated successfully!');
    }
}