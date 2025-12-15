<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #222; }
        .header {
            background: linear-gradient(90deg, #3b82f6 0%, #06b6d4 100%); 
            color: white;
            padding: 10px 16px;
            margin-bottom: 12px;
            border-radius: 4px;
        }
        h3 { margin: 0 0 8px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th, td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
        th {
            background: #D7F4D0; 
            font-weight: bold;
            text-align: left;
        }
        .small { font-size: 10px; color: #444; }
        .center { text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <h3>FACULTY HIRING – {{ $position->name ?? '' }}</h3>
    <div class="small">Generated: {{ \Carbon\Carbon::now()->format('F d, Y') }}</div>
</div>

<table>
    <thead>
        <tr>
            <th>Position</th>
            <th>Present Position</th>
            <th>Education</th>
            <th>Experience</th>
            <th>Training</th>
            <th>Eligibility</th>
            <th>Other Involvement</th>
            <th>CP Number</th>
            <th>Address</th>
            <th>Email Address</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($applications as $a)
        <tr>
            <td>{{ $a->position_name }}</td>
            <td>{{ $a->present_position }}</td>
            <td>{{ $a->education }}</td>
            <td class="center">{{ $a->experience }}</td>
            <td>{{ $a->training }}</td>
            <td>{{ $a->eligibility }}</td>
            <td>{{ $a->other_involvement }}</td>
            <td>{{ $a->phone_number }}</td>
            <td>{{ $a->address }}</td>
            <td>{{ $a->email }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
