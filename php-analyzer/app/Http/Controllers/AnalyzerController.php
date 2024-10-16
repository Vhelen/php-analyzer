<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
                // Get the file name and its content
                $php_files[] = [
                    'name' => $file->getClientOriginalName(),
                    'content' => file_get_contents($file->getRealPath())
                ];
            }
        }

        $analyzerService = new CodeAnalyzerService();

        foreach ($php_files as $php_file) {
            $report = $analyzerService->analyzePHPCode($php_file['content']);

            dd($report);
        }

        // Save or log report and pass it to the view
        return view('analyzer.report', ['report' => $report]);
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
