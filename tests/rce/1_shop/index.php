<?php

// CSS for the page
$css = "
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #343a40;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
        }
        form {
            margin-top: 20px;
        }
        label, input, button {
            display: block;
            margin: 10px 0;
            width: 100%;
            padding: 10px;
        }
        input {
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            background-color: #ffc107;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
";

// Print the CSS
echo $css;

// Print the header
echo "<header><h1>RCE Shop - Welcome</h1></header>";

echo "<div class='container'>";
echo "<h1>Welcome to Our Store!</h1>";
echo "<p>Browse products or login to manage your account.</p>";
echo "</div>";
?>
