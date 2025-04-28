<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 10px;
        }
        .report-info {
            margin-bottom: 20px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
        .sensor-header {
            background-color: #e5e7eb;
            padding: 10px;
            margin: 20px 0 10px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">ClearSky</div>
        <h1>{{ $title }}</h1>
        <div class="report-info">
            Generated on: {{ $generated_at }}
        </div>
    </div>

    @foreach($data as $sensorData)
        <div class="sensor-header">
            <h2>{{ $sensorData['sensor']->name }} ({{ $sensorData['sensor']->location }})</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Reading Value</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sensorData['readings'] as $reading)
                    <tr>
                        <td>{{ $reading['timestamp'] }}</td>
                        <td>{{ $reading['value'] }}</td>
                        <td>{{ getSensorUnit($sensorData['sensor']->type) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="footer">
        ClearSky Air Quality Monitoring System - Page {PAGENO}
    </div>
</body>
</html>