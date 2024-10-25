@extends('layouts.app')

@section('title', 'Analysis Report')

@section('content')
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

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"> <strong>File Name:</strong> {{ $fileInfo['name'] }}</h5>
                <p class="card-text">
                    <strong>File Path:</strong> {{ $fileInfo['path'] ?? 'N/A' }}
                    @if ($total_vulns > 0)
                        <br><br>
                        <strong>Total vulns:</strong> {{ $total_vulns }}
                    @endif
                </p>
            </div>
        </div>
        
        @if ($total_vulns > 0)
            @foreach($vulns_cat as $vuln_cat => $vulns)
                @if(count($vulns['findings']) > 0)
                    <div class="vulns">
                        <strong> {{ Str::upper($vulns['name']) }}:</strong> {{ count($vulns['findings']) }}
                    </div>
                    <table class="table table-striped table-bordered">
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

        <hr class="hr" />
    @endforeach
</div>
@endsection