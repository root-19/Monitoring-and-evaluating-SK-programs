<?php
include "../controller/Controller.php";
include "../database/Database.php";

// Create an instance of the Database class
$db = new Database();
$pdo = $db->getConnection();

// Create an instance of the Database class to get the connection
$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $participantId = $_POST['participant_id'];
    $status = $_POST['status'];

    // Validate input
    if (!in_array($status, ['active', 'ongoing', 'inactive', 'completed'])) {
        die('Invalid status value.');
    }

    // Update the status in the database
    $stmt = $pdo->prepare("UPDATE participants SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $participantId]);

    // Redirect back to the participants page
    header('Location: ../views/admin/participants.php');
    exit;
}