<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class FileController extends Controller
{

    public function findPhpFiles(Request $request)
    {
        // Decode the JSON directory structure sent by the JavaScript
        $files = json_decode($request->input('directory'), true);

        if (!$files || !is_array($files)) {
            return response()->json(['error' => 'Invalid directory data provided.'], 400);
        }

        $phpFiles = array_filter($files, function ($filePath) {
            return pathinfo($filePath, PATHINFO_EXTENSION) === 'php';
        });

        return response()->json(array_values($phpFiles));
    }

    private function getPhpFiles($directory)
    {
        $phpFiles = [];

        // Iterate through the directory and its subdirectories
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        foreach ($iterator as $fileInfo) {
            // Only add PHP files to the list
            if ($fileInfo->isFile() && $fileInfo->getExtension() === 'php') {
                $phpFiles[] = $fileInfo->getRealPath();
            }
        }

        return $phpFiles;
    }
}