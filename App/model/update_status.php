<?php
include "../controller/Controller.php";
include "../database/Database.php";

// Create an instance of the Database class to get the connection
$db = new Database();
$pdo = $db->getConnection();  // Now $pdo is correctly initialized

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = $_POST['category_id'];
    $status = $_POST['status'];

    // Validate input
    if (!in_array($status, ['pending', 'ongoing', 'completed'])) {
        die('Invalid status value.');
    }

    // Update the status in the database
    $stmt = $pdo->prepare("UPDATE categories SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $categoryId]);

    // Redirect back to the main page
    header('Location: ../views/admin/category.php');
    exit;
}
