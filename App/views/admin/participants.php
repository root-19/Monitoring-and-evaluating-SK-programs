<?php
include "../../controller/Controller.php";
include "../../database/database.php";

// Create an instance of the Database class to get the connection
$db = new Database();
$pdo = $db->getConnection();  // Now $pdo is correctly initialized

// Handle form submission for creating a new participant
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_participant'])) {
    // Get POST data
    $name = $_POST['name'];
    $program = $_POST['program'];
    $status = $_POST['status'];
    $date = $_POST['date'];

    // Validate data
    if (empty($name) || empty($program) || empty($status) || empty($date)) {
        echo "All fields are required.";
        exit;
    }

    // Insert the new participant into the database
    $sql = "INSERT INTO participants (name, program, status, date) VALUES (:name, :program, :status, :date)";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':program', $program);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':date', $date);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect or display a success message
        // echo "Participant added successfully!";
        // Optionally, you can redirect to a different page or clear the form inputs here
    } else {
        // If the query fails, show the error
        $error = $stmt->errorInfo();
        echo "Error: " . $error[2];  // Show database error message
    }
}
?>

<!-- Your form remains the same as before -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Participant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<?php include "../public/adminbar.php"; ?>

<body class="bg-gray-100">

    <!-- Form for creating a new participant -->
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Create New Participant</h1>
        
        <form action="participants.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold text-gray-700">Participant Name</label>
                <input type="text" id="name" name="name" required class="w-full p-2 border border-gray-300 rounded-md">
            </div>
            
            <div class="mb-4">
                <label for="program" class="block text-sm font-semibold text-gray-700">Program</label>
                <input type="text" id="program" name="program" required class="w-full p-2 border border-gray-300 rounded-md">
            </div>
            
            <div class="mb-4">
                <label for="status" class="block text-sm font-semibold text-gray-700">Status</label>
                <select id="status" name="status" required class="w-full p-2 border border-gray-300 rounded-md">
                    <option value="pending">Pending</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="date" class="block text-sm font-semibold text-gray-700">Date</label>
                <input type="date" id="date" name="date" required class="w-full p-2 border border-gray-300 rounded-md">
            </div>
            
            <button type="submit" name="create_participant" class="w-full p-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Create Participant
            </button>
        </form>
    </div>

    <!-- Display Participants -->
    <div class="container mx-auto p-4 mt-6">
        <h2 class="text-2xl font-semibold mb-4">Existing Participants</h2>
        
        <table class="w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="p-3 text-left bg-gray-200">Name</th>
                    <th class="p-3 text-left bg-gray-200">Program</th>
                    <th class="p-3 text-left bg-gray-200">Status</th>
                    <th class="p-3 text-left bg-gray-200">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    // Fetch existing participants
                    $sql = "SELECT * FROM participants ORDER BY date DESC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($participants as $participant): ?>
                        <tr class="border-b">
                            <td class="p-3"><?php echo htmlspecialchars($participant['name']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($participant['program']); ?></td>
                            <td class="p-3"><?php echo ucfirst($participant['status']); ?></td>
                            <td class="p-3"><?php echo date('F j, Y', strtotime($participant['date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
