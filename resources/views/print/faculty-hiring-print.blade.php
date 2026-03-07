<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Faculty Hiring - {{ $hiringDate }}</title>
    <style>
        @page {
            size: legal landscape;
            margin: 10mm 12mm 10mm 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1.5px solid #000;
        }

        /* ── Table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        thead tr th {
            background-color: #c6efce;
            border: 1px solid #000;
            padding: 5px 3px;
            text-align: center;
            font-size: 7pt;
            font-weight: bold;
            vertical-align: middle;
            line-height: 1.2;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        tbody tr td {
            border: 1px solid #000;
            padding: 5px 4px;
            font-size: 7.5pt;
            vertical-align: top;
            line-height: 1.3;
        }

        tbody tr:nth-child(even) td {
            background-color: #f2fff2;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        td.center {
            text-align: center;
            vertical-align: middle;
        }

        /* ── Screen preview ── */
        @media screen {
            body { background: #e5e7eb; }
            .page {
                background: white;
                width: 355mm;
                min-height: 216mm;
                margin: 20px auto;
                padding: 12mm;
                box-shadow: 0 0 12px rgba(0,0,0,0.25);
            }
            .print-btn-wrap {
                text-align: center;
                padding: 16px 0 0;
            }
            .print-btn {
                display: inline-block;
                padding: 10px 32px;
                background: #0a6025;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 13px;
                font-weight: bold;
                cursor: pointer;
                margin-bottom: 10px;
            }
            .print-btn:hover { background: #084d1e; }
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
    $rowsPerPage = 10;
    $chunks = array_chunk($applicants, $rowsPerPage);
    if (empty($chunks)) {
        $chunks = [[]]; // at least one page even if no data
    }
@endphp

@foreach($chunks as $pageIndex => $pageData)
<div class="page">

    <!-- Header on every page -->
    <div class="header">
        FACULTY HIRING &mdash; {{ $hiringDate }}
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th style="width:3%;">No.</th>
                <th style="width:16%;">POSITION</th>
                <th style="width:9%;">PRESENT<br>POSITION</th>
                <th style="width:12%;">EDUCATION</th>
                <th style="width:13%;">EXPERIENCE</th>
                <th style="width:9%;">TRAINING</th>
                <th style="width:13%;">ELIGIBILITY</th>
                <th style="width:7%;">OTHER<br>INVOLVEMENTS</th>
                <th style="width:7%;">CP NUMBER</th>
                <th style="width:11%;">ADDRESS</th>
                <th style="width:0%;">EMAIL ADDRESS</th>
            </tr>
            {{-- Example/sample row showing what data looks like per column --}}
            <tr style="background-color:#e8f5e9; -webkit-print-color-adjust:exact; print-color-adjust:exact;">
                <th style="font-weight:normal; font-size:6.5pt; text-align:center; border:1px solid #000; padding:4px 3px;"></th>
                <th style="font-weight:normal; font-size:6.5pt; text-align:left; border:1px solid #000; padding:4px 3px;">
                    Instructor I<br>CLSU.INST-1-6-2019
                </th>
                <th style="font-weight:normal; font-size:6.5pt; text-align:left; border:1px solid #000; padding:4px 3px;">Present Position</th>
                <th style="font-weight:normal; font-size:6.5pt; text-align:left; border:1px solid #000; padding:4px 3px;">MA/MS in Social Science</th>
                <th style="font-weight:normal; font-size:6.5pt; text-align:left; border:1px solid #000; padding:4px 3px;">Two (2) years of teaching and relevant experience</th>
                <th style="font-weight:normal; font-size:6.5pt; text-align:left; border:1px solid #000; padding:4px 3px;">None Required</th>
                <th style="font-weight:normal; font-size:6.5pt; text-align:left; border:1px solid #000; padding:4px 3px;">None Required<br>(Ra 1080 For Courses requiring BAR or BOARD Eligibility)</th>
                <th style="font-weight:normal; font-size:6.5pt; text-align:center; border:1px solid #000; padding:4px 3px;"></th>
                <th style="font-weight:normal; font-size:6.5pt; text-align:center; border:1px solid #000; padding:4px 3px;">CP NUMBER</th>
                <th style="font-weight:normal; font-size:6.5pt; text-align:left; border:1px solid #000; padding:4px 3px;">ADDRESS</th>
                <th style="font-weight:normal; font-size:6.5pt; text-align:left; border:1px solid #000; padding:4px 3px;">EMAIL ADDRESS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pageData as $i => $row)
            <tr>
                <td class="center">{{ $pageIndex * $rowsPerPage + $i + 1 }}</td>
                <td>
                    <strong>{{ $row['position_name'] }}</strong><br>
                    <span style="font-size:6.5pt; font-style:italic;">{{ $row['name'] }}</span>
                </td>
                <td>{{ $row['present_pos'] }}</td>
                <td>{{ $row['education'] }}</td>
                <td>{{ $row['experience'] }}</td>
                <td>{{ $row['training'] }}</td>
                <td>{{ $row['eligibility'] }}</td>
                <td class="center">{{ $row['other'] }}</td>
                <td class="center">{{ $row['cp_number'] }}</td>
                <td>{{ $row['address'] }}</td>
                <td>{{ $row['email'] }}</td>
            </tr>
            @endforeach

            {{-- Fill remaining blank rows up to 10 --}}
            @for($i = count($pageData); $i < $rowsPerPage; $i++)
            <tr>
                <td class="center">{{ $pageIndex * $rowsPerPage + $i + 1 }}</td>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
            </tr>
            @endfor
        </tbody>
    </table>

</div>
@endforeach

<script>
    window.onload = function () {
        window.print();
    };
</script>
</body>
</html>