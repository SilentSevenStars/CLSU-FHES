<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>NBC Report</title>
    <style>
        @page {
            margin: 15mm 20mm;
            size: 8.5in 13in portrait;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.3;
        }

        .header-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .header-section p {
            margin: 2px 0;
            font-size: 11px;
        }

        .info-section {
            margin: 15px 0;
        }

        .info-row {
            margin: 8px 0;
        }

        .underline {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 200px;
            padding: 0 5px;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        table.main-table th {
            background: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        table.main-table td {
            font-size: 10px;
        }



        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .font-bold   { font-weight: bold; }
        .clear       { clear: both; }

        /* ── 3-column signature layout ── */
        .sig-section {
            width: 100%;
            margin-top: 50px;
            display: table;
            table-layout: fixed;
            border: 0;
        }

        .sig-col {
            display: table-cell;
            vertical-align: top;
            font-size: 10px;
            padding-right: 10px;
        }

        .sig-col:last-child { padding-right: 0; }

        .sig-col-label {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 20px;
        }

        .sig-entry {
            margin-bottom: 22px;
        }

        .sig-line {
            border-bottom: 1px solid #000;
            width: 180px;
            margin-bottom: 3px;
        }

        .sig-name {
            font-weight: bold;
        }

        .sig-role {
            font-size: 10px;
        }

        /* Evaluation Date — sits at bottom right after signatures */
        .evaluation-date {
            position: fixed;
            bottom: 10mm;
            right: 20mm;
            font-size: 10px;
            text-align: right;
        }

        /* ── Screen ── */
        @media screen {
            body { background: #e5e7eb; }
            .page-wrap {
                background: white;
                width: 8.5in;
                min-height: 13in;
                margin: 20px auto;
                padding: 15mm 20mm;
                box-shadow: 0 0 12px rgba(0,0,0,0.25);
                box-sizing: border-box;
            }
        }

        /* ── Print ── */
        @media print {
            body { background: white; }
            .page-wrap { margin: 0; padding: 0; box-shadow: none; width: 100%; }
            .print-btn-wrap { display: none; }
        }
    </style>
</head>
<body>

    <div class="print-btn-wrap" style="text-align:center; margin:16px 0;">
        <button onclick="window.print()"
                style="padding:10px 32px; background:#0a6025; color:white; border:none; border-radius:8px; font-size:13px; font-weight:bold; cursor:pointer;">
            🖨️ Print
        </button>
    </div>

    <div class="page-wrap">

        <!-- Header -->
        <div class="header-section">
            <p><strong>Republic of the Philippines</strong></p>
            <p><strong>Central Luzon State University</strong></p>
            <p><strong>University Evaluation Committee for Hiring</strong></p>
            <p><strong>Science City of Munoz, Nueva Ecija</strong></p>
        </div>

        <!-- Info fields -->
        <div class="info-section">
            <div class="info-row">
                Name of Faculty: <span class="underline">{{ $data['name'] }}</span>
            </div>
            <div class="info-row">
                Present Rank: <span class="underline" style="min-width:160px;">&nbsp;</span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                College / Campus: <span class="underline">{{ $data['college'] }}</span>
            </div>
            <div class="info-row">
                Rank Applied for: <span class="underline">{{ $data['position'] }}</span>
            </div>
        </div>

        <!-- Title -->
        <div style="text-align:center; margin:20px 0;">
            <p style="margin:2px 0;"><strong>PASUC COMMON CRITERIA FOR EVALUATION</strong></p>
            <p style="margin:2px 0;"><strong>OF FACULTY UNDER NBC 461</strong></p>
            <p style="margin:2px 0;"><strong>SUMMARY OF POINTS</strong></p>
            <p style="margin:2px 0;">Cut-off Period: _______________</p>
        </div>

        <!-- Main Table -->
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width:30%;">MAJOR<br>COMPONENTS</th>
                    <th style="width:15%;">MAXIMUM<br>POINTS</th>
                    <th style="width:20%; text-align:center;">
                        PREVIOUS<br>POINTS<br>AS OF<br><br>
                    </th>
                    <th style="width:20%; text-align:center;">
                        ADDITIONAL<br>POINTS<br>AS OF<br><br>
                    </th>
                    <th style="width:15%;">TOTAL<br>POINTS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>1.0</strong>&nbsp;&nbsp;&nbsp; Educational<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Qualification</td>
                    <td class="text-center">85</td>
                    <td class="text-center">{{ $data['previous_education'] }}</td>
                    <td class="text-center">{{ $data['additional_education'] }}</td>
                    <td class="text-center font-bold">{{ $data['total_education'] }}</td>
                </tr>
                <tr>
                    <td><strong>2.0</strong>&nbsp;&nbsp;&nbsp; Experience and<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Length of Service</td>
                    <td class="text-center">25</td>
                    <td class="text-center">{{ $data['previous_experience'] }}</td>
                    <td class="text-center">{{ $data['additional_experience'] }}</td>
                    <td class="text-center font-bold">{{ $data['total_experience'] }}</td>
                </tr>
                <tr>
                    <td><strong>3.0</strong>&nbsp;&nbsp;&nbsp; Professional<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Development,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Achievement<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and Honors</td>
                    <td class="text-center">90</td>
                    <td class="text-center">{{ $data['previous_professional'] }}</td>
                    <td class="text-center">{{ $data['additional_professional'] }}</td>
                    <td class="text-center font-bold">{{ $data['total_professional'] }}</td>
                </tr>
                <tr>
                    <td class="font-bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</td>
                    <td class="text-center font-bold">200</td>
                    <td class="text-center font-bold">{{ $data['previous_total'] }}</td>
                    <td class="text-center font-bold">{{ $data['additional_total'] }}</td>
                    <td class="text-center font-bold">{{ $data['grand_total'] }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right font-bold" style="border-right:none;">Projected&nbsp;&nbsp;Points</td>
                    <td class="text-center font-bold" style="border-left:none;">{{ $data['projected_points'] }}</td>
                </tr>
            </tbody>
        </table>

        @php
            // Left column  — evaluators 1–4
            // Middle column — evaluators 5–7  (aligned to row 1 of left column)
            // Right column  — Approved by / Chairperson
            $leftEvaluators   = array_slice($evaluators, 0, 4);
            $middleEvaluators = array_slice($evaluators, 4, 3);

            // Pad to keep row count consistent
            while (count($leftEvaluators)   < 4) $leftEvaluators[]   = null;
            while (count($middleEvaluators) < 3) $middleEvaluators[] = null;
        @endphp

        <!-- 3-Column Signature Section -->
        <div class="sig-section">

            <!-- LEFT: Evaluation Committee (4 evaluators) -->
            <div class="sig-col" style="width:38%;">
                <div class="sig-col-label">EVALUATION COMMITTEE</div>
                @foreach($leftEvaluators as $ev)
                <div class="sig-entry">
                    <div class="sig-line"></div>
                    @if($ev)
                        <div class="sig-name">{{ $ev }}</div>
                    @endif
                    <div class="sig-role">Evaluator</div>
                </div>
                @endforeach
            </div>

            <!-- MIDDLE: 3 evaluators — first row aligns with first left evaluator -->
            <div class="sig-col" style="width:38%;">
                {{-- Spacer pushes entries down past the "EVALUATION COMMITTEE" label --}}
                <div style="height:30px;">&nbsp;</div>
                @foreach($middleEvaluators as $ev)
                <div class="sig-entry">
                    <div class="sig-line"></div>
                    @if($ev)
                        <div class="sig-name">{{ $ev }}</div>
                    @endif
                    <div class="sig-role">Evaluator</div>
                </div>
                @endforeach
            </div>

            <!-- RIGHT: Approved by -->
            <div class="sig-col" style="width:24%;">
                <div style="height:30px;">&nbsp;</div>
                <div style="font-weight:bold; margin-bottom:40px;">Approved:</div>
                <div class="sig-entry">
                    <div class="sig-line"></div>
                    @if($chairperson)
                        <div class="sig-name">{{ $chairperson }}</div>
                    @endif
                    <div class="sig-role">CLSU NBC 461 Chairperson</div>
                </div>
            </div>

        </div>

        <!-- Evaluation Date — fixed bottom-right -->
        <div class="evaluation-date">
            <strong>Evaluation Date:</strong> {{ \Carbon\Carbon::parse($data['interview_date'])->format('F d, Y') }}
        </div>

        <div class="clear"></div>

    </div>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 200);
        });
    </script>

</body>
</html>