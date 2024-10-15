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

// Simulated routing for handling different pages
$page = $_GET['page'] ?? 'home';

function renderHome() {
    echo "<div class='container'>";
    echo "<h1>Welcome to Our Store!</h1>";
    echo "<p>Browse products or login to manage your account.</p>";
    echo "</div>";
}

function renderProduct() {
    $productId = $_GET['id'] ?? 1; // Assume default product ID if none is provided
    echo "<div class='container'>";
    // Vulnerable: user input is directly passed to front -> xss
    echo "<h1>Product Details for Product #$productId</h1>"; 
    echo "<p>Product description goes here.</p>";
    echo "</div>";
}

// Function: Admin can upload scripts
function renderAdminPanel() {
    if ($_GET['admin'] == 'true') {
        echo "<div class='container'>";
        echo "<h1>Admin Panel</h1>";
        echo "<form method='POST' action='?page=admin&admin=true&upload=true'>";
        echo "<label for='command'>Command:</label>";
        echo "<input type='text' name='command' id='command' placeholder='Enter command'>";
        echo "<button type='submit'>Run Command</button>";
        echo "</form>";

        // Remote Code Execution vulnerability
        if (isset($_GET['upload']) && $_POST['command']) {
            $command = $_POST['command'];
            echo "<div class='message'>Running command: $command</div>";
            // Vulnerable: user input is directly passed to system function -> rce
            system($command);
        }
        echo "</div>";
    } else {
        echo "<div class='container'>";
        echo "<h1>Access Denied</h1>";
        echo "<p>You do not have permission to access this page.</p>";
        echo "</div>";
    }
}

// Print the header
echo "<header><h1>RCE Shop</h1></header>";

// Routing logic
switch ($page) {
    case 'home':
        renderHome();
        break;
    case 'product':
        renderProduct();
        break;
    case 'admin':
        renderAdminPanel();
        break;
    default:
        echo "<div class='container'><h1>404 Page Not Found</h1></div>";
        break;
}
?>
