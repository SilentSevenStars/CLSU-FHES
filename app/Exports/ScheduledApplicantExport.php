<?php

namespace App\Exports;

use App\Models\JobApplication;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScheduledApplicantExport implements FromArray, WithHeadings, WithStyles, WithEvents
{
    protected $positionId;

    public function __construct($positionId)
    {
        $this->positionId = $positionId;
    }

    protected function sanitize($value)
    {
        if (is_null($value)) return '';
        return (string) iconv('UTF-8', 'UTF-8//IGNORE', $value);
    }

    public function headings(): array
    {
        return [
            'Position',
            'Present Position',
            'Education',
            'Experience',
            'Training',
            'Eligibility',
            'Other Involvement',
            'CP Number',
            'Address',
            'Email Address',
        ];
    }

    public function array(): array
    {
        return JobApplication::with(['applicant.user', 'position'])
            ->where('position_id', $this->positionId)
            ->get()
            ->map(function ($a) {
                return [
                    $this->sanitize(optional($a->position)->name),
                    $this->sanitize($a->present_position),
                    $this->sanitize($a->education),
                    $this->sanitize($a->experience),
                    $this->sanitize($a->training),
                    $this->sanitize($a->eligibility),
                    $this->sanitize($a->other_involvement),
                    $this->sanitize(optional($a->applicant)->phone_number),
                    $this->sanitize(optional($a->applicant)->address),
                    $this->sanitize(optional(optional($a->applicant)->user)->email),
                ];
            })->toArray();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $headerRange = 'A1:J1';

                $sheet->getStyle($headerRange)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('D7F4D0');

                $sheet->getStyle($headerRange)->getAlignment()->setHorizontal('center')->setVertical('center');

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $fullRange = 'A1:' . $highestColumn . $highestRow;

                $sheet->getStyle($fullRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                foreach (range('A', 'J') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $sheet->freezePane('A2');
            },
        ];
    }
}
