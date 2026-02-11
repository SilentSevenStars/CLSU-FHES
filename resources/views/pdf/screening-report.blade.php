<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Comparative Assessment Form</title>
    <style>
        @page {
            margin: 10mm 15mm 10mm 15mm;
            size: legal landscape;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.2;
            color: #000;
        }

        .page {
            page-break-after: always;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        .header {
            margin-bottom: 15px;
        }

        .header-left {
            text-align: left;
            font-size: 8pt;
            margin-bottom: 2px;
        }

        .university-name {
            font-weight: bold;
            font-size: 11pt;
            margin: 2px 0;
        }

        .sub-header {
            font-size: 9pt;
            margin: 1px 0;
        }

        .form-title {
            font-weight: bold;
            font-size: 10pt;
            margin: 8px 0 3px 0;
        }

        .position-name {
            text-align: left;
            font-style: italic;
            font-size: 9pt;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }

        th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
        }

        td {
            border: 1px solid #000;
            padding: 5px 4px;
            font-size: 9pt;
            text-align: center;
        }

        td.text-left {
            text-align: left;
        }

        .footer {
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .signature-section {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .signature-row {
            display: table-row;
        }

        .signature-cell {
            display: table-cell;
            text-align: center;
            padding: 3px;
            font-size: 7pt;
            vertical-align: top;
        }

        .signature-name {
            font-weight: bold;
            font-size: 8pt;
            margin-bottom: 2px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin: 2px 20px 0 20px;
        }

        .signature-title {
            font-size: 6pt;
            margin-top: 2px;
        }

        .prepared-by {
            font-size: 9pt;
        }

        .center-text {
            text-align: center;
        }

        .rank-number {
            font-weight: bold;
        }

        .form-footer {
            position: relative;
            margin-top: 10px;
            font-size: 7pt;
            color: #666;
        }

        .revision-info {
            text-align: left;
        }
    </style>
</head>

<body>
    @php
    // Always use 10 rows per page
    $rowsPerPage = 10;
    $dataChunks = array_chunk($screeningData, $rowsPerPage);
    $totalPages = count($dataChunks);
    @endphp

    @foreach($dataChunks as $pageIndex => $pageData)
    <div class="page">
        <!-- Header - Appears on every page -->
        <div class="header">
            <div class="header-left">COMPARATIVE ASSESSMENT FORM</div>

            <table style="border: none; margin-bottom: 2px;">
                <tr style="border: none;">
                    <td style="border: none; width: 15%; text-align: right; vertical-align: top; padding-right: 5px;">
                        <img src="{{ public_path('image/clsu.png') }}" alt="CLSU Logo"
                            style="height: 50px; width: auto; margin-top: 0;">
                    </td>
                    <td style="border: none; text-align: center; vertical-align: middle;">
                        <div style="font-size: 9pt;">Republic of the Philippines</div>
                        <div class="university-name">CENTRAL LUZON STATE UNIVERSITY</div>
                        <div class="sub-header">Science City of Muñoz, Nueva Ecija</div>
                    </td>
                    <td style="border: none; width: 15%;"></td>
                </tr>
            </table>

            <div class="sub-header center-text">HUMAN RESOURCE MANAGEMENT OFFICE</div>

            <div class="form-title center-text">SUMMARY OF EVALUATION</div>
            <div class="form-title center-text">SCREENING OF APPLICANTS FOR FACULTY POSITIONS</div>
        </div>

        <!-- Position Name - Appears on every page -->
        <div class="position-name">{{ $positionName }}</div>

        <!-- Main Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">NAME OF APPLICANTS</th>
                    <th style="width: 18%;">FIELD OF SPECIALIZATION</th>
                    <th style="width: 12%;">Performance (SPMS)<br><span
                            style="font-weight: normal; font-size: 6pt;">(Max 30 pts.)</span></th>
                    <th style="width: 14%;">Credentials & Related<br>Experiences<br><span
                            style="font-weight: normal; font-size: 6pt;">(Max 200 pts.)</span></th>
                    <th style="width: 12%;">Interview<br><span style="font-weight: normal; font-size: 6pt;">(Max 80
                            pts.)</span></th>
                    <th style="width: 12%;">TOTAL</th>
                    <th style="width: 12%;">RANK</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pageData as $data)
                <tr>
                    <td class="text-left">{{ $data['name'] }}</td>
                    <td class="text-left">{{ $data['specialization'] }}</td>
                    <td>{{ number_format($data['performance'], 2) }}</td>
                    <td>{{ number_format($data['credentials_experience'], 2) }}</td>
                    <td>{{ number_format($data['interview'], 2) }}</td>
                    <td style="font-weight: bold;">{{ number_format($data['total'], 2) }}</td>
                    <td><span class="rank-number">{{ $data['rank'] }}</span></td>
                </tr>
                @endforeach

                {{-- Fill remaining rows to make exactly 10 data rows per page --}}
                @for($i = count($pageData); $i < $rowsPerPage; $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor

                {{-- One blank row after the 10 data rows --}}
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                {{-- Prepared by row - appears on every page --}}
                <tr>
                    <td class="text-left prepared-by"><strong>Prepared by: Jameka S. Lucido</strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer with Signatures - Appears on every page -->
        <div class="footer">
            <!-- 1st Row - Members -->
            <div class="signature-section">
                <div class="signature-row">
                    <div class="signature-cell" style="width: 25%;">
                        <div class="signature-name">{{ $panelMembers['supervising_admin'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Member, Supervising Admin. Officer, HRMO</div>
                    </div>
                    <div class="signature-cell" style="width: 25%;">
                        <div class="signature-name">{{ $panelMembers['fai_president'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Member, FAI President/Representative</div>
                    </div>
                    <div class="signature-cell" style="width: 25%;">
                        <div class="signature-name">{{ $panelMembers['glutches_preside'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Member, GLUTCHES Preside</div>
                    </div>
                    <div class="signature-cell" style="width: 25%;">
                        <div class="signature-name">{{ $panelMembers['ranking_faculty'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Member, Ranking Faculty</div>
                    </div>
                </div>
            </div>

            <!-- 2nd Row - Deans -->
            <div class="signature-section">
                <div class="signature-row">
                    <div class="signature-cell" style="width: 16.66%;">
                        <div class="signature-name">{{ $panelMembers['dean_cass'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Dean, CASS</div>
                    </div>
                    <div class="signature-cell" style="width: 16.66%;">
                        <div class="signature-name">{{ $panelMembers['dean_cen'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Dean, CEN Representative</div>
                    </div>
                    <div class="signature-cell" style="width: 16.66%;">
                        <div class="signature-name">{{ $panelMembers['dean_cos'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Dean, COS</div>
                    </div>
                    <div class="signature-cell" style="width: 16.66%;">
                        <div class="signature-name">{{ $panelMembers['dean_ced'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Dean, CED</div>
                    </div>
                    <div class="signature-cell" style="width: 16.66%;">
                        <div class="signature-name">{{ $panelMembers['dean_cf'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Dean, CF</div>
                    </div>
                    <div class="signature-cell" style="width: 16.66%;">
                        <div class="signature-name">{{ $panelMembers['dean_cba'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Dean, CBA</div>
                    </div>
                </div>
            </div>

            <!-- 3rd Row - Senior Faculty and Heads -->
            <div class="signature-section">
                <div class="signature-row">
                    <div class="signature-cell" style="width: 20%;">
                        <div class="signature-name">{{ $panelMembers['senior_faculty'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Senior Faculty</div>
                    </div>
                    <div class="signature-cell" style="width: 20%;">
                        <div class="signature-name">{{ $panelMembers['head_dabe'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Head, Dept DABE, Representative</div>
                    </div>
                    <div class="signature-cell" style="width: 20%;">
                        <div class="signature-name">{{ $panelMembers['head_business'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Head, Dept Business</div>
                    </div>
                    <div class="signature-cell" style="width: 20%;">
                        <div class="signature-name">{{ $panelMembers['head_ispels'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Head, ISPELS</div>
                    </div>
                    <div class="signature-cell" style="width: 20%;">
                        <div class="signature-name">{{ $panelMembers['chairman_fsb'] }}</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Chairman, Faculty Selection Board & VPAA</div>
                    </div>
                </div>
            </div>

            <!-- 4th Row - University President (Centered) -->
            <div class="center-text" style="margin-top: 8px;">
                <div class="signature-name" style="font-weight: bold;">{{ $panelMembers['university_president'] }}</div>
                <div class="signature-line" style="width: 250px; margin: 3px auto;"></div>
                <div class="signature-title" style="font-size: 7pt;">University President</div>
            </div>
        </div>

        <!-- Form Footer with Revision Info - Appears on every page -->
        <div class="form-footer">
            <div class="revision-info">
                ADM.ADS.HRM.F.036 (Revision No. 0; June 29, 2021)
            </div>
        </div>
    </div>
    @endforeach
</body>

</html>