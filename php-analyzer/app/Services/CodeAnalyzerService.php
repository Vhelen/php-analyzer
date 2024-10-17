<?php

namespace App\Services;

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;

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
        $rce_visitor = new RemoteCodeExecutionVisitor();
        $sqli_visitor = new SQLInjectionVisitor();
        
        // Add it to visit node
        $traverser->addVisitor($rce_visitor);
        $traverser->addVisitor($sqli_visitor);

        // écéparti
        $traverser->traverse($ast);

        // Get results
        $var_findings = $variableFinder->getVariableDefinitions();
        
        // did we get some vulns ?
        $rce_findings = $rce_visitor->getResults();
        $sqli_findings = $sqli_visitor->getResults();

        // Retrieve results from the visitor
        return ["vars" => $var_findings, "vulns" => ["rce" => $rce_findings, "sqli" => $sqli_findings]];
    }
}
