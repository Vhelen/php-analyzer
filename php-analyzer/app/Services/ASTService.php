<?php

namespace App\Services;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter\Standard;

class ASTService extends NodeVisitorAbstract
{
    private $variables = [];

    function extractVariablesFromConcat($node)
    {
        $temp_variables = [];
    
        // Recursively traverse left and right
        if ($node instanceof Node\Expr\BinaryOp\Concat) {
            // Recur for the left side
            $temp_variables = array_merge($temp_variables, $this->extractVariablesFromConcat($node->left));
            // Recur for the right side
            $temp_variables = array_merge($temp_variables, $this->extractVariablesFromConcat($node->right));
        } elseif ($node instanceof Node\Expr\Variable) {
            // If it's a variable, add it to the list

            $this->variables[] = $node->name;
        }
    
        return $this->variables;
    }

    public function get_vars()
    {
        return $this->variables;
    }

    function reconstructPHPCode($node)
    {
        $prettyPrinter = new Standard();

        // Recursively reconstruct left and right nodes
        if ($node instanceof Node\Expr\BinaryOp\Concat) {
            // Reconstruct the left and right parts
            $leftCode = $this->reconstructPHPCode($node->left);
            $rightCode = $this->reconstructPHPCode($node->right);

            // Return the concatenation expression as PHP code
            return $leftCode . ' . ' . $rightCode;
        } elseif ($node instanceof Node\Expr\Variable) {
            // Return the variable name as PHP code
            return '$' . $node->name;
        } elseif ($node instanceof Node\Scalar\String_) {
            // Return the string value as PHP code
            return '"' . $node->value . '"';
        }

        // Default to using PhpParser's pretty printer if it's a different node type
        return $prettyPrinter->prettyPrintExpr($node);
    }

    function whereAreThisVarComeFrom(){
        

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


}
