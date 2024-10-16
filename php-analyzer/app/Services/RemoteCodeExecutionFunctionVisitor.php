<?php

namespace App\Services;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class RemoteCodeExecutionFunctionVisitor extends NodeVisitorAbstract
{
    private $results = [];

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\FuncCall) {
            $functionName = $node->name->toString();
            $dangerousFunctions = ['eval', 'exec', 'system', 'shell_exec', 'passthru', ];

            if (in_array($functionName, $dangerousFunctions)) {
                $this->results[] = [
                    'function' => $functionName,
                    'line' => $node->getLine(),
                    'message' => "Dangerous function detected: $functionName",
                    'args' => $node->args
                ];
            }
        }
    }

    public function getResults()
    {
        return $this->results;
    }
}
