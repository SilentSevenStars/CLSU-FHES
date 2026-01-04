<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ScreeningExportController extends Controller
{
    public function export(Request $request)
    {
        $screeningData = $request->input('data', []);
        $positionName = $request->input('position_name', 'Various Positions');
        $interviewDate = $request->input('interview_date', now()->format('M d, Y'));

        // Get panel members for footer
        $panelMembers = $this->getPanelMembers();

        $pdf = Pdf::loadView('pdf.screening-report', [
            'screeningData' => $screeningData,
            'positionName' => $positionName,
            'interviewDate' => $interviewDate,
            'panelMembers' => $panelMembers,
            'generatedDate' => now()->format('F d, Y'),
        ]);

        // Set to legal size (long bond paper) in landscape orientation
        $pdf->setPaper('legal', 'landscape');

        return $pdf->download('screening-report-' . now()->format('Y-m-d') . '.pdf');
    }

    private function getPanelMembers()
    {
        return [
            'supervising_admin' => 'Member, Supervising Admin. Officer, HRMO',
            'fai_president' => 'Member, FAI President/Representative',
            'glutches_preside' => 'Member, GLUTCHES Preside',
            'ranking_faculty' => 'Member, Ranking Faculty',
            'dean_cass' => 'Dean, CASS',
            'dean_cen' => 'Dean, CEN Representative',
            'dean_cos' => 'Dean, COS',
            'dean_ced' => 'Dean, CED',
            'dean_cf' => 'Dean, CF',
            'dean_cba' => 'Dean, CBA',
            'senior_faculty' => 'Senior Faculty',
            'head_dept_dabe' => 'Head, Dept DABE, Representative',
            'head_dept_business' => 'Head, Dept Business',
            'head_ispels' => 'Head, ISPELS',
            'chairman_fsb' => 'Chairman, Faculty Selection Board & VPAA',
            'university_president' => 'University President',
        ];
    }
}