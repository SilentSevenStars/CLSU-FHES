<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>NBC Report</title>
    <style>
        @page {
            margin: 15mm 20mm;
            size: legal portrait;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        th {
            background: #f5f5f5;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        td {
            font-size: 10px;
        }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .font-bold   { font-weight: bold; }
        .clear       { clear: both; }

        /* Remove the internal dividing line between the two header rows
           for "PREVIOUS POINTS / POINTS AS OF" and "ADDITIONAL POINTS / POINTS AS OF" */
        thead tr:first-child th.no-bottom-border {
            border-bottom: none;
        }
        thead tr:last-child th.no-top-border {
            border-top: none;
        }

        /* Evaluation Date fixed to bottom-right like a page number */
        .evaluation-date {
            position: fixed;
            bottom: 10mm;
            right: 20mm;
            font-size: 10px;
            text-align: right;
        }

        /* ── Screen-only styles ── */
        @media screen {
            body { background: #e5e7eb; }
            .page-wrap {
                background: white;
                width: 210mm;
                margin: 20px auto;
                padding: 15mm 20mm;
                box-shadow: 0 0 12px rgba(0,0,0,0.25);
            }
            /* screen-only print button styles removed */
        }

        /* ── Print-only styles ── */
        @media print {
            body { background: white; }
            .page-wrap { margin: 0; padding: 0; box-shadow: none; width: 100%; }
            .print-btn-wrap { display: none; }
        }
    </style>
</head>
<body>

    <!-- print button: allows manual retry when auto dialog is cancelled -->
    <div class="print-btn-wrap" style="text-align:center; margin:16px 0;">
        <button class="print-btn" onclick="window.print()" 
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

        <!-- Information Section -->
        <div class="info-section">
            <div class="info-row">
                Name of Faculty: <span class="underline">{{ $data['name'] }}</span>
            </div>
            <div class="info-row">
                Present Rank: <span class="underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                College / Campus: <span class="underline">{{ $data['college'] }}</span>
            </div>
            <div class="info-row">
                Rank Applied for: <span class="underline">{{ $data['position'] }}</span>
            </div>
        </div>

        <!-- Title Section -->
        <div style="text-align: center; margin: 20px 0;">
            <p style="margin: 2px 0;"><strong>PASUC COMMON CRITERIA FOR EVALUATION</strong></p>
            <p style="margin: 2px 0;"><strong>OF FACULTY UNDER NBC 461</strong></p>
            <p style="margin: 2px 0;"><strong>SUMMARY OF POINTS</strong></p>
            <p style="margin: 2px 0;">Cut-off Period: _______________</p>
        </div>

        <!-- Main Table -->
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 30%;">MAJOR<br>COMPONENTS</th>
                    <th rowspan="2" style="width: 15%;">MAXIMUM<br>POINTS</th>
                    {{-- no-bottom-border removes the line between "PREVIOUS POINTS" and "POINTS AS OF" --}}
                    <th class="no-bottom-border" style="width: 20%;">PREVIOUS POINTS</th>
                    {{-- no-bottom-border removes the line between "ADDITIONAL POINTS" and "POINTS AS OF" --}}
                    <th class="no-bottom-border" style="width: 20%;">ADDITIONAL POINTS</th>
                    <th rowspan="2" style="width: 15%;">TOTAL<br>POINTS</th>
                </tr>
                <tr>
                    {{-- no-top-border pairs with no-bottom-border above --}}
                    <th class="no-top-border">POINTS AS OF</th>
                    <th class="no-top-border">POINTS AS OF</th>
                </tr>
            </thead>

            <tbody>
                <!-- Educational Qualification -->
                <tr>
                    <td><strong>1.0</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Educational<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Qualification</td>
                    <td class="text-center">85</td>
                    <td class="text-center">{{ $data['previous_education'] }}</td>
                    <td class="text-center">{{ $data['additional_education'] }}</td>
                    <td class="text-center font-bold">{{ $data['total_education'] }}</td>
                </tr>

                <!-- Experience and Length of Service -->
                <tr>
                    <td><strong>2.0</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Experience and<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Length of Service</td>
                    <td class="text-center">25</td>
                    <td class="text-center">{{ $data['previous_experience'] }}</td>
                    <td class="text-center">{{ $data['additional_experience'] }}</td>
                    <td class="text-center font-bold">{{ $data['total_experience'] }}</td>
                </tr>

                <!-- Professional Development -->
                <tr>
                    <td><strong>3.0</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Professional<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Development,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Achievement<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;and Honors</td>
                    <td class="text-center">90</td>
                    <td class="text-center">{{ $data['previous_professional'] }}</td>
                    <td class="text-center">{{ $data['additional_professional'] }}</td>
                    <td class="text-center font-bold">{{ $data['total_professional'] }}</td>
                </tr>

                <!-- Total Row -->
                <tr style="background: #e8e8e8;">
                    <td class="font-bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</td>
                    <td class="text-center font-bold">200</td>
                    <td class="text-center font-bold">{{ $data['previous_total'] }}</td>
                    <td class="text-center font-bold">{{ $data['additional_total'] }}</td>
                    <td class="text-center font-bold">{{ $data['grand_total'] }}</td>
                </tr>

                <!-- Projected Points Row -->
                <tr>
                    <td colspan="4" class="text-right font-bold" style="border-right: none;">Projected&nbsp;&nbsp;Points</td>
                    <td class="text-center font-bold" style="border-left: none;">{{ $data['projected_points'] }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Signature Section -->
        <table style="width:100%; margin-top:60px; border-collapse:collapse; border:0; outline:none; box-shadow:none;">
            <tr style="border:0;">
                <!-- LEFT: Evaluation Committee -->
                <td style="width:60%; vertical-align:top; font-size:10px; border:0; outline:none;">
                    <strong>EVALUATION COMMITTEE</strong>
                    <br><br>

                    _______________________________<br>
                    <strong>EFREN DELA CRUZ</strong><br>
                    Evaluator
                    <br><br>

                    _______________________________<br>
                    <strong>MERCEDITA M. REYES</strong><br>
                    Evaluator
                    <br><br>

                    _______________________________<br>
                    <strong>MA. ELIZABETH C. LEOVERAS</strong><br>
                    Evaluator
                </td>

                <!-- RIGHT: Approved -->
                <td style="width:40%; vertical-align:top; font-size:10px; text-align:left; border:0; outline:none;">
                    <strong>Approved:</strong>
                    <br><br><br>

                    _______________________________<br>
                    <strong>JANET O. SATURNO</strong><br>
                    CLSU NBC 461 Chairperson
                </td>
            </tr>
        </table>

        <!-- Evaluation Date — fixed bottom-right like a page number -->
        <div class="evaluation-date">
            <strong>Evaluation Date:</strong> {{ \Carbon\Carbon::parse($data['interview_date'])->format('F d, Y') }}
        </div>

        <div class="clear"></div>

    </div><!-- end .page-wrap -->

    <script>
        // automatically open print dialog when new tab loads; user may cancel
        // and can click the button above to retry.
        window.addEventListener('load', function() {
            setTimeout(function() { window.print(); }, 200);
        });
    </script>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>

</body>
</html>