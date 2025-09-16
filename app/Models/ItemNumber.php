<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemNumber extends Model
{
  use HasFactory;

  protected $fillable = [
    'item_number',
    'position_id',
    'salary_grade_id',
    'employment_status_id',
    'status',
  ];

  public function position()
  {
    return $this->belongsTo(Position::class);
  }

  public function salaryGrade()
  {
    return $this->belongsTo(SalaryGrade::class);
  }

  public function employmentStatus()
  {
    return $this->belongsTo(EmploymentStatus::class);
  }
}
