<?php

namespace App\Services;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter\Standard;

class RemoteCodeExecutionVisitor extends NodeVisitorAbstract
{
    private $results = [];
    private $prettyPrinter;

    public function __construct()
    {
        // Initialize the pretty printer
        $this->prettyPrinter = new Standard();
    }

    public function enterNode(Node $node)
    {
        // Check for function calls
        if ($node instanceof Node\Expr\FuncCall) {
            $functionName = $node->name->toString();

            // List of dangerous functions related to remote code execution
            $dangerousRCEFunctions = [
                'eval', 'exec', 'shell_exec', 'system', 'passthru', 'popen', 'proc_open', 'pcntl_exec',
            ];

            // Check if the function is in the list of dangerous RCE functions
            if (in_array($functionName, $dangerousRCEFunctions)) {
                // Check if any of the arguments come from user input ($_GET, $_POST, etc.)
                foreach ($node->args as $arg) {
                    // Get the PHP code of the node
                    $code = $this->prettyPrinter->prettyPrint([$node]);

                    $variables = $this->extractVariables($arg->value);

                    $this->results[] = [
                        'function' => $functionName,
                        'line' => $node->getLine(),
                        'args' => $this->getArgumentValues($node->args),
                        'vars' => $variables,
                        'message' => "Potential Remote Code Execution vulnerability detected: $functionName",
                        'code' => $code
                    ];
                    break;
                }
            }
        }
    }

    private function getArgumentValues($args)
    {
        $values = [];
        foreach ($args as $arg) {
            if ($arg->value instanceof Node\Scalar\String_) {
                $values[] = $arg->value->value;
            } elseif ($arg->value instanceof Node\Expr\Variable) {
                $values[] = '$' . $arg->value->name;
            } else {
                $values[] = 'Complex Expression';
            }
        }
        return $values;
    }

    public function getResults()
    {
        return $this->results;
    }

    private function extractVariables($node)
    {
        $variables = [];

        // Recursively check nodes for variables
        if ($node instanceof Node\Expr\Variable) {
            $variables[] = $node->name;
        } elseif ($node instanceof Node\Expr\ArrayDimFetch && $node->var instanceof Node\Expr\Variable) {
            // For cases like $_GET['key'], add $_GET
            $variables[] = $node->var->name;
        } elseif ($node instanceof Node\Expr) {
            // Recursively check nested expressions for variables
            foreach ($node->getSubNodeNames() as $subNodeName) {
                $subNode = $node->$subNodeName;
                if (is_array($subNode)) {
                    foreach ($subNode as $innerNode) {
                        $variables = array_merge($variables, $this->extractVariables($innerNode));
                    }
                } elseif ($subNode instanceof Node) {
                    $variables = array_merge($variables, $this->extractVariables($subNode));
                }
            }
        }

        return array_unique($variables);
    }
}