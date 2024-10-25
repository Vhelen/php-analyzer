<?php

namespace App\Services;

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;

use Illuminate\Support\Facades\File;

class CodeAnalyzerService
{
    public function analyzePHPCode($php_code)
    {
        $parser = (new ParserFactory())->createForNewestSupportedVersion();

        try {
            $ast = $parser->parse($php_code);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return;
        }

        $traverser = new NodeTraverser;

        // Find where var is def
        $variableFinder = new VariableDefinitionFinderService();
        $traverser->addVisitor($variableFinder);

        // Find some vulns

        // load json vulns
        $vulnerabilities = File::json(resource_path('vulnerabilities.json'));

        $rce_visitor = new Visitor($vulnerabilities['RCE']['functions']);
        $traverser->addVisitor($rce_visitor);

        $sqli_visitor = new Visitor($vulnerabilities['SQLI']['functions']);
        $traverser->addVisitor($sqli_visitor);

        $xss_visitor = new Visitor($vulnerabilities['XSS']['functions']);
        $traverser->addVisitor($xss_visitor);

        $csrf_visitor = new Visitor($vulnerabilities['CSRF']['functions']);
        $traverser->addVisitor($csrf_visitor);

        $dir_trav_visitor = new Visitor($vulnerabilities['DirTrav']['functions']);
        $traverser->addVisitor($dir_trav_visitor);

        $cmdi_visitor = new Visitor($vulnerabilities['CMDI']['functions']);
        $traverser->addVisitor($cmdi_visitor);

        $rce_2_visitor = new RemoteCodeExecutionVisitor();
        $traverser->addVisitor($rce_2_visitor);

        // écéparti
        $traverser->traverse($ast);

        // Get results
        $var_findings = $variableFinder->getVariableDefinitions();

        $test = $rce_2_visitor->getResults();
        // if($test) dd($test);
        
        // did we get some vulns ?
        $vulnerabilities['RCE']['findings'] = $rce_visitor->getResults();
        $vulnerabilities['SQLI']['findings'] = $sqli_visitor->getResults();
        $vulnerabilities['XSS']['findings'] = $xss_visitor->getResults();
        $vulnerabilities['CSRF']['findings'] = $csrf_visitor->getResults();
        $vulnerabilities['DirTrav']['findings'] = $dir_trav_visitor->getResults();
        $vulnerabilities['CMDI']['findings'] = $cmdi_visitor->getResults();

        // Retrieve results from the visitor
        return ["vars" => $var_findings, "vulns" => $vulnerabilities];
    }
}
