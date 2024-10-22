<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

use App\Services\CodeAnalyzerService;


class AnalyzerController extends Controller
{
    // Display the homepage/form
    public function index()
    {
        return view('analyzer.index');
    }

    // Handle file upload and start the analysis process
    public function analyze(Request $request)
    {
        // Validate the user input
        $request->validate([
            'directory' => 'required|array'
        ]);

        // Get the files from the input
        $files = $request->file('directory');

        $php_files = [];

        foreach ($files as $file) {
            if ($file->getClientOriginalExtension() === 'php') {
                // Get the file name, path and its content
                $php_files[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => "", // TODO
                    'content' => file_get_contents($file->getRealPath())
                ];
            }
        }

        $analyzerService = new CodeAnalyzerService();

        $reports = [];

        foreach ($php_files as $php_file) {
            // Security problem -> log injection with filename -> no filter on filename upload by user
            // TODO : patch log
            Log::info(Str::padRight('', Str::length('|  Analyze file: '.$php_file['name'].'  |'), '-'));
            Log::info('|  Analyze file: '.$php_file['name'].'  |');
            Log::info(Str::padRight('', Str::length('|  Analyze file: '.$php_file['name'].'  |'), '-'));
            
            $results = $analyzerService->analyzePHPCode($php_file['content']);

            $var_defs = [];

            // TODO Refacto
            // function to find where vars used in dangerous function is declared
            foreach($results['vulns'] as $vuln_cat => $vuln_findings){
                if($vuln_findings['findings']) {
                    foreach($vuln_findings['findings'] as $vuln_finding) {    
                        // link var used in dangerous command to where there are def
                        foreach($vuln_finding["vars"] as $var) {
                            if(in_array($var, $results['vars'])){
                                $var_defs[$var] = $results['vars'][$var];
                            } else {
                                // var def not found
                            }
                        }
                    }
                }
            }
            /*
            Report Format

            
            */
            
            // New reports
            $reports[] = [$php_file, $results];
        }

        // Save or log report and pass it to the view
        return view('analyzer.report', ['reports' => $reports]);
    }

    // Display a specific report (based on ID, for example)
    public function showReport($id)
    {
        // Fetch the report data from the database or logs
        $report = Report::find($id);

        if (!$report) {
            abort(404, 'Report not found');
        }

        return view('analyzer.show', ['report' => $report]);
    }
}
