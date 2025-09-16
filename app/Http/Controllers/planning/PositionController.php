<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\SalaryGrade;
use App\Models\EmploymentStatus;
use App\Models\Qualification;

class PositionController extends Controller
{

  public function index()
  {
    $positions = Position::with(['salaryGrade', 'employmentStatus'])->get();
    $salaryGrades = SalaryGrade::all();
    $employmentStatuses = EmploymentStatus::all();
    $qualifications = Qualification::all(); // ✅ required

    return view('content.planning.position', compact(
      'positions',
      'salaryGrades',
      'employmentStatuses',
      'qualifications'
    ));
  }


  public function store(Request $request)
  {
    $validated = $request->validate([
      'position_name' => 'required|string|max:255',
      'abbreviation' => 'required|string|max:50',
    ]);

    $validated['position_name'] = strtoupper($validated['position_name']);
    $validated['abbreviation'] = strtoupper($validated['abbreviation']);

    $position = Position::create($validated);

    return response()->json([
      'success' => true,
      'position_id' => $position->id, // return saved ID
    ]);
  }

  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'position_name' => 'required|string|max:255',
      'abbreviation' => 'required|string|max:50',
    ]);

    $validated['position_name'] = strtoupper($validated['position_name']);
    $validated['abbreviation'] = strtoupper($validated['abbreviation']);

    $position = Position::findOrFail($id);
    $position->update($validated);

    return response()->json([
      'success' => true,
      'position_id' => $position->id,
    ]);
  }
  public function destroy($id)
  {
    $position = Position::findOrFail($id);
    $position->delete();

    return response()->json(['success' => true]);
  }
}
