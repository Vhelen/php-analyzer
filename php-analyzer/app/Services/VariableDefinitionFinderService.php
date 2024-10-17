<?php

namespace App\Services;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeFinder;
use PhpParser\PrettyPrinter\Standard;

class VariableDefinitionFinderService extends NodeVisitorAbstract
{
    private $variableDefinitions = [];

    public function enterNode(Node $node)
    {
        // Check for variable assignments
        if ($node instanceof Node\Expr\Assign) {
            $left = $node->var; // The left side of the assignment
            $right = $node->expr; // The right side of the assignment

            // If the left side is a variable
            if ($left instanceof Node\Expr\Variable) {
                $variableName = $left->name;
                $this->variableDefinitions[$variableName][] = [
                    'line' => $node->getLine(),
                    'code' => $this->prettyPrintExpr($node)
                ];
            }
        }
    }

    public function getVariableDefinitions()
    {
        return $this->variableDefinitions;
    }

    // Helper function to pretty-print the node (reconstruct the PHP code)
    private function prettyPrintExpr($node)
    {
        $prettyPrinter = new Standard();
        return $prettyPrinter->prettyPrintExpr($node);
    }
}
