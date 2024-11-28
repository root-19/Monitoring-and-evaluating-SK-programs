<?php
// Start session to check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Check if the user has been approved
require_once(__DIR__ . '../controller/Controller.php'); 
require_once(__DIR__ . '../database/database.php');
$database = new Database();
$pdo = $database->getConnection();
$userManager = new Controller($pdo);

// Fetch the user status
$userStatus = $userManager->getUserStatus($_SESSION['user_id']); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .alert {
            background-color: #fefcbf;
            color: #7a7a7a;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Account Status</h2>

        <?php
        if ($userStatus !== 'approved') {
            // If account is awaiting approval
            echo '<div class="alert">Your account is awaiting approval. Please wait for admin approval.</div>';
        } else {
            // If account is approved
            header('Location: index.php'); // Redirect to main page when approved
            exit();
        }
        ?>

        <a href="index.php" class="btn">Log Out</a>
    </div>
</body>

</html>