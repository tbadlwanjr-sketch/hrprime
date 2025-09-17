<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
  use HasFactory;

  protected $fillable = [
    'position_name',
    'abbreviation',
    'status',
    'salary_grade_id',
    'division_id',
    'section_id',
    'employment_status_id',
    'position_level_id',
  ];

  // Relationships
  public function salaryGrade()
  {
    return $this->belongsTo(SalaryGrade::class);
  }

  public function division()
  {
    return $this->belongsTo(Division::class);
  }

  public function section()
  {
    return $this->belongsTo(Section::class);
  }

  public function employmentStatus()
  {
    return $this->belongsTo(EmploymentStatus::class, 'employment_status_id');
  }

  public function positionLevel()
  {
    return $this->belongsTo(PositionLevel::class);
  }

  /**
   * A position can have many qualifications.
   */
  public function qualifications()
  {
    return $this->hasMany(Qualification::class, 'position_id');
  }

  /**
   * A position can have many requirements.
   */
  public function requirements()
  {
    return $this->hasMany(Requirement::class, 'position_id');
  }
}
