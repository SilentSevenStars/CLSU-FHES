<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 15mm 20mm;
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

        th,
        td {
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

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .signature-section {
            margin-top: 50px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .signature-row {
            margin-bottom: 25px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 350px;
            margin-bottom: 3px;
        }

        .signature-label {
            font-size: 10px;
            text-transform: uppercase;
        }

        .right-signature {
            float: right;
            text-align: center;
            margin-top: -150px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>

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
            Present Rank: <span
                class="underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
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
                <th rowspan="2" style="width: 25%;">MAJOR<br>COMPONENTS</th>
                <th rowspan="2" style="width: 10%;">MAXIMUM<br>POINTS</th>
                <th style="width: 13%;">PREVIOUS<br>POINTS<br>AS OF</th>
                <th style="width: 13%;">ADDITIONAL<br>POINTS<br>AS OF</th>
                <th rowspan="2" style="width: 13%;">EP<br>SUBTOTAL</th>
                <th rowspan="2" style="width: 13%;">TOTAL<br>POINTS</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <!-- Educational Qualification -->
            <tr>
                <td><strong>1.0</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Educational<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Qualification
                </td>
                <td class="text-center">85</td>
                <td class="text-center">{{ $data['previous_education'] }}</td>
                <td class="text-center">{{ $data['additional_education'] }}</td>
                <td class="text-center font-bold">{{ $data['ep_education_subtotal'] }}</td>
                <td class="text-center font-bold">{{ $data['total_education'] }}</td>
            </tr>

            <!-- Experience and Length of Service -->
            <tr>
                <td><strong>2.0</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Experience
                    and<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Length of
                    Service</td>
                <td class="text-center">25</td>
                <td class="text-center">{{ $data['previous_experience'] }}</td>
                <td class="text-center">{{ $data['additional_experience'] }}</td>
                <td class="text-center font-bold">{{ $data['ep_experience_subtotal'] }}</td>
                <td class="text-center font-bold">{{ $data['total_experience'] }}</td>
            </tr>

            <!-- Professional Development -->
            <tr>
                <td><strong>3.0</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Professional<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Development,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Achievement<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;and
                    Honors</td>
                <td class="text-center">90</td>
                <td class="text-center">{{ $data['previous_professional'] }}</td>
                <td class="text-center">{{ $data['additional_professional'] }}</td>
                <td class="text-center font-bold">{{ $data['ep_professional_subtotal'] }}</td>
                <td class="text-center font-bold">{{ $data['total_professional'] }}</td>
            </tr>

            <!-- Total Row -->
            <tr style="background: #e8e8e8;">
                <td class="font-bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</td>
                <td class="text-center font-bold">200</td>
                <td class="text-center font-bold">{{ $data['previous_total'] }}</td>
                <td class="text-center font-bold">{{ $data['additional_total'] }}</td>
                <td class="text-center font-bold">{{ $data['ep_total_subtotal'] }}</td>
                <td class="text-center font-bold">{{ $data['grand_total'] }}</td>
            </tr>

            <!-- Projected Points Row -->
            <tr>
                <td colspan="5" class="text-right font-bold" style="border-right: none;">Projected&nbsp;&nbsp;Points
                </td>
                <td class="text-center font-bold" style="border-left: none;">{{ $data['projected_points'] }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Signature Section -->
    <table style="
    width:100%;
    margin-top:60px;
    border-collapse:collapse;
    border:0;
    outline:none;
    box-shadow:none;
">
        <tr style="border:0;">
            <!-- LEFT: Evaluation Committee -->
            <td style="
            width:60%;
            vertical-align:top;
            font-size:10px;
            border:0;
            outline:none;
        ">
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
            <td style="
            width:40%;
            vertical-align:top;
            font-size:10px;
            text-align:left;
            border:0;
            outline:none;
        ">
                <strong>Approved:</strong>
                <br><br><br>

                _______________________________<br>
                <strong>JANET O. SATURNO</strong><br>
                CLSU NBC 461 Chairperson
            </td>
        </tr>
    </table>

    <div class="clear"></div>

</body>

</html>