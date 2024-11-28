<?php
include "../controller/Controller.php";
include "../database/Database.php";

// Create an instance of the Database class to get the connection
$db = new Database();
$pdo = $db->getConnection();  // Now $pdo is correctly initialized

// Check if the form is submitted via POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_category'])) {
    // Get POST data
    $title = $_POST['title'];
    $message = $_POST['message'];
    $status = $_POST['status'];
    $date = $_POST['date'];

    // Debug: Check if the POST data is received
    var_dump($_POST); // Check the form data
    // exit; // Do not exit here during actual form submission

    // Validate data
    if (empty($title) || empty($message) || empty($status) || empty($date)) {
        echo "All fields are required.";
        exit;
    }

    // Insert the new category into the database
    $sql = "INSERT INTO categories (title, message, status, date) VALUES (:title, :message, :status, :date)";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':date', $date);

    // Execute the statement
    if ($stmt->execute()) {
        // Return the new category row for AJAX appending
        $newCategory = [
            'title' => htmlspecialchars($title),
            'message' => htmlspecialchars($message),
            'status' => ucfirst($status),
            'date' => date('F j, Y', strtotime($date)),
        ];

        echo "<tr class='border-b'>
                <td class='p-3'>{$newCategory['title']}</td>
                <td class='p-3'>{$newCategory['message']}</td>
                <td class='p-3'>{$newCategory['status']}</td>
                <td class='p-3'>{$newCategory['date']}</td>
            </tr>";
    } else {
        // If the query fails, show the error
        $error = $stmt->errorInfo();
        echo "Error: " . $error[2];  // Show database error message
    }
}
?>
