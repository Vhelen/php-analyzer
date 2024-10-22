<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis Report</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f1f1f1;
        }
        .file-info {
            margin-top: 20px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
        }
        .no-vulns {
            margin-top: 20px;
            background-color: #d4edda;
            padding: 10px;
            border-radius: 5px;
            color: #155724;
        }
        .vulns {
            margin-top: 20px;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP Code Analysis Report</h1>

        @foreach ($reports as $report)
            @php
                $fileInfo = $report[0];

                $vars = $report[1]['vars'];
                $vulns_cat = $report[1]['vulns'];

                $total_vulns = 0;
            @endphp

            @foreach($vulns_cat as $vuln_cat => $vulns)
                @php $total_vulns += count($vulns['findings']); @endphp
            @endforeach

           
            <div class="file-info">
                <strong>File Name:</strong> {{ $fileInfo['name'] }}<br>
                <strong>File Path:</strong> {{ $fileInfo['path'] ?? 'N/A' }}
                
                @if ($total_vulns > 0)
                    <br><br>
                    <strong>Total vulns:</strong> {{ $total_vulns }}
                @endif
            </div>
            
            @if ($total_vulns > 0)
                @foreach($vulns_cat as $vuln_cat => $vulns)
                    @if(count($vulns['findings']) > 0)
                        <div class="vulns">
                            <strong> {{ Str::upper($vulns['name']) }}:</strong> {{ count($vulns['findings']) }}
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Function</th>
                                    <th>Line</th>
                                    <th>Arguments</th>
                                    <th>Variables</th>
                                    <th>Code</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vulns['findings'] as $vuln)
                                    <tr>
                                        <td>{{ $vuln['function'] }}</td>
                                        <td>{{ $vuln['line'] }}</td>
                                        <td>
                                            @if (isset($vuln['args']))
                                                @if(is_array($vuln['args']))
                                                    @foreach ($vuln['args'] as $arg)
                                                        <code>{{ $arg }}</code><br>
                                                    @endforeach
                                                @else
                                                    <code>{{ $vuln['args'] }}
                                                @endif
                                            @else
                                                <em>No arguments</em>
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($vuln['vars']) && is_array($vuln['vars']))
                                                @foreach ($vuln['vars'] as $var)

                                                    @if(array_key_exists($var, $vars))
                                                        @foreach($vars[$var] as $def)
                                                            <code>Line {{ $def['line'] }}: {{ $def['code'] }}</code><br>
                                                        @endforeach
                                                    @else
                                                        Var <code>${{ $var }}</code> not found.<br>
                                                    @endif

                                                @endforeach
                                            @else
                                                <em>No arguments</em>
                                            @endif
                                        </td>
                                        <td><code>{{ $vuln['code'] }}</code></td>
                                        <td>{{ $vuln['message'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endforeach
            @else
                <div class="no-vulns">
                    No vulnerabilities detected in this file.
                </div>
            @endif
        @endforeach
    </div>
</body>
</html>
