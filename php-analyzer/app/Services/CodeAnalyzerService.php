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

        $visitor = new RemoteCodeExecutionFunctionVisitor();

        $traverser->addVisitor($visitor);

        $traverser->traverse($ast);

        // Retrieve results from the visitor
        return $visitor->getResults();
    }
}
