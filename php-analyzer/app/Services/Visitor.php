<?php

namespace App\Services;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter\Standard;

class Visitor extends NodeVisitorAbstract
{
    private $results = [];
    private $prettyPrinter;

    public function __construct(Array $functions)
    {
        // Initialize the pretty printer
        $this->prettyPrinter = new Standard();
        $this->functions = $functions;
    }

    public function enterNode(Node $node)
    {
        // Check for function calls or method calls
        if ($node instanceof Node\Expr\FuncCall || $node instanceof Node\Expr\MethodCall) {
            
            $function_name = $node instanceof Node\Expr\FuncCall 
                ? $node->name->toString() 
                : $node->name;

            // Check if the function/method is in the list of dangerous functions
            if (in_array($function_name, $this->functions)) {
                
                

                foreach ($node->args as $arg) {
                    if ($this->isUserInput($arg->value)) {
                        // Check if arg come from user input ($_GET, $_POST, etc.)
                        // todo
                        // need to check were var is def to see if user input also here
                    }

                    $code = $this->prettyPrinter->prettyPrint([$node]);

                    $variables = $this->extractVariables($arg->value);

                    $this->results[] = [
                        'function' => $function_name,
                        'line' => $node->getLine(),
                        'args' => $this->getArgumentValues($node->args),
                        'vars' => $variables,
                        'message' => "Potential SQL Injection vulnerability detected: $function_name",
                        'code' => $code
                    ];
                }
            }
        }
    }

    private function isUserInput($node)
    {
        // Check if the node is a global variable like $_GET, $_POST, $_REQUEST, etc.
        if ($node instanceof Node\Expr\ArrayDimFetch) {
            $varName = $node->var->name;

            $superglobals = ['_GET', '_POST', '_REQUEST', '_COOKIE'];

            return in_array($varName, $superglobals);
        }
        return false;
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
