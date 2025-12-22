<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\Division;

class SectionSeeder extends Seeder
{
  public function run(): void
  {
    $sections = [
      ['ORD', 'Regional Sub-Committee for the Welfare of Children', 'RSCWC'],
      ['ORD', 'Legal Unit', 'LU'],
      ['ORD', 'Internal Audit Unit', 'Internal Audit'],
      ['ORD', 'Social Marketing Unit', 'SMU'],
      ['PSD', 'Social Technology Unit', 'SOCTECH'],
      ['ORD', 'Regional Juvenile Justice and Welfare Council', 'RJWC'],
      ['ORD', 'Office of the Assistant Regional Director for Operations', 'OARDO'],
      ['ORD', 'Office of the Assistant Regional Director for Administration', 'OARDA'],
      ['PSD', 'Sustainable Livelihood Program Management Office', 'SLP'],
      ['PSD', 'KALAHI CIDSS Program Management Office', 'KALAHI-CIDSS'],
      ['DRMD', 'Disaster Response and Rehabilitation Section', 'DRRS'],
      ['DRMD', 'Regional Resource Operations Section', 'RROS'],
      ['DRMD', 'Disaster Response Information Management Section', 'DRIMS'],
      ['PSD', 'Crisis Intervention Section', 'CIS'],
      ['PSD', 'Capability Building Section', 'CapBuild'],
      ['PSD', 'Technical Assistance and Resource Augmentation', 'TARU'],
      ['PSD', 'Community-Based Services Section', 'CBSS'],
      ['PSD', 'Social Pension Program Management Office', 'SocPen'],
      ['PSD', 'Supplimentary Feeding Program Management Office', 'SFP'],
      ['PSD', 'Regional Alternative Child Care Office', 'RACCO'],
      ['PSD', 'Center Based Services', 'CBS'],
      ['PSD', 'Home for the Aged', 'HA'],
      ['PSD', 'Regional Rehabilitation Center for Youth', 'RRCY'],
      ['PSD', 'Center for Children with Special Needs', 'CCSN'],
      ['PSD', 'Reception and Study Center for Children', 'RSCC'],
      ['PSD', 'Home for Girls and Women', 'HGW'],
      ['4Ps', 'Pantawid Pamilyang Pilipino Program Management Division', '4Ps'],
      ['PPD', 'Policy Development and Planning Section', 'PDPS'],
      ['ORD', 'Anti-Red Tape Unit', 'ARTU'],
      ['PPD', 'Standards Section', 'Standards'],
      ['PPD', 'Information and Communications Technology Management Section', 'ICTMS'],
      ['PPD', 'National Household Targeting System Program Management Office', 'NHTS'],
      ['PPD', 'Unconditional Cash Transfer Program Management Office', 'UCT'],
      ['AD', 'Property Supply and Asset Management Section', 'PSAM'],
      ['AD', 'Procurement Section', 'Procurement'],
      ['AD', 'Records and Archives Management Section', 'RAMS'],
      ['AD', 'General Services Section', 'GSS'],
      ['HRMDD', 'Human Resource Planning and Performance Management Section', 'HRPPM'],
      ['HRMDD', 'Personnel Administration Section', 'HR-PAS'],
      ['HRMDD', 'Learning Development Section', 'HR-LDS'],
      ['HRMDD', 'Welfare Section', 'HR-Welfare'],
      ['FMD', 'Accounting Section', 'Accounting'],
      ['FMD', 'Budget Section', 'Budget'],
      ['FMD', 'Cash Section', 'Cash'],
      ['ORD', 'Office of the Regional Director', 'ORD'],
      ['PSWDO', 'Provincial Social Welfare and Development Office (PSWADO)', 'PSWDO'],
      ['PSD', 'EPHAP', 'EPHAP'],
      ['ORD', 'Secretary of the Director', null],
      ['PSD', 'PAG-ABOT', null],
    ];

    foreach ($sections as [$divisionCode, $name, $abbr]) {

      $division = Division::where('abbreviation', $divisionCode)->first();

      if (!$division) {
        // Skip if division does not exist (prevents crash)
        continue;
      }

      Section::updateOrCreate(
        [
          'division_id' => $division->id,
          'name' => $name,
        ],
        [
          'abbreviation' => $abbr,
        ]
      );
    }
  }
}
