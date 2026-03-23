<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>NBC Evaluation Report</title>
    <style>
        @page {
            margin: 10mm 12mm 10mm 12mm;
            size: A4 landscape;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #000;
        }

        .page {
            page-break-after: always;
            display: flex;
            flex-direction: column;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        /* ── Header ── */
        .header-meta {
            font-size: 7pt;
            margin-bottom: 2px;
        }

        .header-table {
            width: 100%;
            border: none;
            margin-bottom: 3px;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            padding: 1px 4px;
        }

        .university-name {
            font-weight: bold;
            font-size: 10.5pt;
        }

        .sub-header {
            font-size: 8.5pt;
        }

        .form-title {
            font-weight: bold;
            font-size: 9.5pt;
            text-align: center;
            margin: 3px 0 2px;
        }

        .report-meta {
            font-size: 8pt;
            margin: 4px 0 6px;
            border: 1px solid #ccc;
            padding: 4px 8px;
            background: #f9f9f9;
        }

        .report-meta table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .report-meta td {
            border: none;
            padding: 1px 6px;
            font-size: 8pt;
        }

        /* ── Main Table ── */
        table.main {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
        }

        table.main th {
            background-color: #d9d9d9;
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
            font-size: 7.5pt;
            font-weight: bold;
        }

        table.main td {
            border: 1px solid #000;
            padding: 4px 3px;
            font-size: 8pt;
            text-align: center;
            vertical-align: middle;
        }

        table.main td.text-left {
            text-align: left;
        }

        .score-zero {
            color: #9ca3af;
            font-style: italic;
        }

        .score-value {
            font-weight: 600;
            color: #166534;
        }

        .total-score {
            font-weight: bold;
            font-size: 8.5pt;
        }

        .total-zero {
            font-weight: bold;
            font-size: 8.5pt;
            color: #9ca3af;
            font-style: italic;
        }

        .status-complete {
            font-weight: bold;
            color: #166534;
        }

        .status-pending {
            font-weight: bold;
            color: #92400e;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 10px;
            font-size: 7.5pt;
        }

        .footer-note {
            font-style: italic;
            margin-bottom: 8px;
        }

        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .sig-table td {
            border: none;
            text-align: center;
            padding: 3px 8px;
            font-size: 7.5pt;
            vertical-align: top;
            width: 50%;
        }

        .sig-line {
            border-bottom: 1px solid #000;
            margin: 2px 20px 0 20px;
        }

        .sig-name {
            font-weight: bold;
            font-size: 8.5pt;
            margin-bottom: 2px;
        }

        .sig-title {
            font-size: 7pt;
            margin-top: 2px;
        }

        .form-footer {
            margin-top: 8px;
            font-size: 6.5pt;
            color: #666;
        }

        /* ── Screen only ── */
        @media screen {
            body { background: #e5e7eb; }
            .page {
                background: white;
                /* A4 landscape width */
                width: 272mm;
                margin: 20px auto;
                padding: 10mm 12mm;
                box-shadow: 0 0 12px rgba(0,0,0,0.25);
            }
            .print-btn-wrap {
                text-align: center;
                padding: 16px 0 0;
            }
            .print-btn {
                display: inline-block;
                padding: 10px 32px;
                background: #1d4ed8;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 13px;
                font-weight: bold;
                cursor: pointer;
                margin-bottom: 10px;
            }
            .print-btn:hover { background: #1e40af; }
        }

        @media print {
            body { background: white; }
            .page { margin: 0; padding: 0; box-shadow: none; width: 100%; }
            .print-btn-wrap { display: none; }
        }
    </style>
</head>
<body>

<div class="print-btn-wrap">
    <button class="print-btn" onclick="window.print()">🖨️ Print</button>
</div>

@php
    // Support both dashboard-style printing (multiple rows) and the
    // admin single-record case.
    if (!isset($reportData)) {
        $reportData = isset($data) ? [$data] : [];
    }

    $interviewDate   = $interviewDate   ?? ($reportData[0]['interview_date'] ?? '');
    $totalApplicants = $totalApplicants ?? count($reportData);
    $completedCount  = $completedCount  ?? collect($reportData)->where('status', 'Complete')->count();
    $pendingCount    = $pendingCount    ?? ($totalApplicants - $completedCount);

    $rowsPerPage = 12;   // fewer rows per page because of the extra columns on landscape
    $dataChunks  = array_chunk($reportData, $rowsPerPage);
    $totalPages  = count($dataChunks);
    if (empty($dataChunks)) {
        $dataChunks = [[]];
        $totalPages = 1;
    }
@endphp

@foreach($dataChunks as $pageIndex => $pageRows)
<div class="page">

    <!-- Header -->
    <div class="header-meta">NBC EVALUATION REPORT</div>

    <table class="header-table">
        <tr>
            <td style="width: 10%; text-align: right; vertical-align: middle;">
                <img src="{{ asset('image/clsu.png') }}" alt="CLSU Logo" style="height: 46px; width: auto;">
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <div style="font-size: 8.5pt;">Republic of the Philippines</div>
                <div class="university-name">CENTRAL LUZON STATE UNIVERSITY</div>
                <div class="sub-header">Science City of Muñoz, Nueva Ecija</div>
            </td>
            <td style="width: 10%;"></td>
        </tr>
    </table>

    <div class="sub-header" style="text-align:center;">HUMAN RESOURCE MANAGEMENT OFFICE</div>
    <div class="form-title">NBC EVALUATION — LIST OF APPLICANTS</div>

    <!-- Report meta -->
    <div class="report-meta">
        <table>
            <tr>
                <td><strong>Interview / Deadline Date:</strong> {{ $interviewDate }}</td>
                <td><strong>Total Applicants:</strong> {{ $totalApplicants }}</td>
                <td><strong>Completed:</strong> {{ $completedCount }}</td>
                <td><strong>Pending:</strong> {{ $pendingCount }}</td>
            </tr>
        </table>
    </div>

    <!-- Main Table -->
    <table class="main">
        <thead>
            <tr>
                <th style="width: 4%;">No.</th>
                <th style="width: 20%;">Name of Applicant</th>
                <th style="width: 17%;">Position Applied</th>
                <th style="width: 16%;">Email</th>
                <th style="width: 10%;">Educational<br>Qualification<br>Score</th>
                <th style="width: 10%;">Experience<br>Service<br>Score</th>
                <th style="width: 10%;">Professional<br>Development<br>Score</th>
                <th style="width: 7%;">Total<br>Score</th>
                <th style="width: 6%;">Date of<br>Evaluation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pageRows as $row)
            @php
                $isPending = ($row['status'] === 'Pending');
            @endphp
            <tr>
                <td>{{ $row['number'] }}</td>
                <td class="text-left">{{ $row['name'] }}</td>
                <td class="text-left">{{ $row['position'] }}</td>
                <td class="text-left" style="font-size: 7pt;">{{ $row['email'] }}</td>

                {{-- Educational Qualification Score --}}
                <td>
                    @if($isPending)
                        <span class="score-zero">0.000</span>
                    @else
                        <span class="score-value">{{ $row['edu_score'] }}</span>
                    @endif
                </td>

                {{-- Experience Service Score --}}
                <td>
                    @if($isPending)
                        <span class="score-zero">0.000</span>
                    @else
                        <span class="score-value">{{ $row['exp_score'] }}</span>
                    @endif
                </td>

                {{-- Professional Development Score --}}
                <td>
                    @if($isPending)
                        <span class="score-zero">0.000</span>
                    @else
                        <span class="score-value">{{ $row['pro_score'] }}</span>
                    @endif
                </td>

                {{-- Total Score --}}
                <td>
                    @if($isPending)
                        <span class="total-zero">0.000</span>
                    @else
                        <span class="total-score">{{ $row['total_score'] }}</span>
                    @endif
                </td>

                {{-- Date of Evaluation — blank if pending --}}
                <td>
                    @if(!empty($row['evaluation_date']))
                        {{ $row['evaluation_date'] }}
                    @else
                        &nbsp;
                    @endif
                </td>
            </tr>
            @endforeach

            {{-- Fill blank rows to maintain consistent table height --}}
            @for($i = count($pageRows); $i < $rowsPerPage; $i++)
            <tr>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
            </tr>
            @endfor
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-note">
            Page {{ $pageIndex + 1 }} of {{ $totalPages }}
            &nbsp;&nbsp;|&nbsp;&nbsp;
            Generated: {{ $generatedDate }}
            &nbsp;&nbsp;|&nbsp;&nbsp;
            Scores shown are per evaluating NBC committee member. Pending rows show 0 until evaluation is submitted.
        </div>

        <table class="sig-table">
            <tr>
                <td>
                    <div class="sig-name">&nbsp;</div>
                    <div class="sig-line"></div>
                    <div class="sig-title">Prepared by (NBC Committee Member)</div>
                </td>
                <td>
                    <div class="sig-name">&nbsp;</div>
                    <div class="sig-line"></div>
                    <div class="sig-title">Noted by (HRMO Officer)</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="form-footer">
        ADM.ADS.HRM.NBC.F.001 (Revision No. 0; {{ $generatedDate }})
    </div>

</div>
@endforeach

<script>
    window.onload = function () {
        window.print();
    };
</script>
</body>
</html>