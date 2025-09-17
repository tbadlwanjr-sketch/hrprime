<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Models\Applicants;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ApplicantController extends Controller
{
        public function index()
        {
            $applicants = Applicants::where('archived', false)->get();
             return view('content.planning.applicants', compact('applicants'));
        }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'sex'           => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'date_applied'  => 'required|date',
            'status'        => 'required'
        ]);

        Applicants::create($request->all());

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $applicants = Applicants::findOrFail($id);

        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'sex'           => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'date_applied'  => 'required|date',
            'status'        => 'required'
        ]);

        $applicants->update($request->all());

        return response()->json(['success' => true]);
    }

            public function archive($id)
            {
                $applicants = Applicants::findOrFail($id);
                $applicants->update(['archived' => true]); // mark as archived, not deleted

                return response()->json(['success' => true]);
            }
}
