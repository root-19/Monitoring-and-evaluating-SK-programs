<?php
include "../../controller/Controller.php";
include "../../database/database.php";

// Create an instance of the Database class to get the connection
$db = new Database();
$pdo = $db->getConnection();  

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $rating = intval($_POST['rating']);
    $feedback = $_POST['feedback'];

    // Prepare the SQL query with placeholders
    $sql = "INSERT INTO feedback (product_name, rating, feedback, date) VALUES (:product_name, :rating, :feedback, NOW())";

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':feedback', $feedback);

    // Execute the statement
    if ($stmt->execute()) {
        $success_message = "Feedback submitted successfully!";
    } else {
        $error_message = "Error: " . implode(", ", $stmt->errorInfo());
    }
}

// Fetch all feedback
$sql = "SELECT * FROM feedback ORDER BY date DESC";
$stmt = $pdo->query($sql);

$feedback = [];
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $feedback[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<header class="bg-blue-700 text-white shadow-md">
    <div class="container mx-auto flex items-center justify-between px-4 py-4">
        <!-- Logo and Title -->
        <div class="flex items-center space-x-4">
            <!-- <img src="../../assets/image/sks.png" alt="Logo" class="w-12 h-13 object-cover rounded"> -->
            <h1 class="text-2xl font-bold">Sangguniang Kabataan Dinagat Islands</h1>
        </div>
        
        <!-- Navigation -->
        <nav>
            <ul class="flex space-x-4 text-sm font-medium">
                <!-- <li><a href="#" class="hover:text-gray-200">Home</a></li>
                <li><a href="#" class="hover:text-gray-200">Categories</a></li>
                <li><a href="#" class="hover:text-gray-200">Participants</a></li>
                <li> -->
    <!-- <a href="signin.php" 
       class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
       Login
    </a> -->
</li>

            </ul>
        </nav>
    </div>
</header>
    <?php include "../public/adminbar.php"; ?>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Feedback Page</h1>

        <!-- Display success or error messages -->
        <?php if (!empty($success_message)): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                <?php echo $success_message; ?>
            </div>
        <?php elseif (!empty($error_message)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Main layout: Form on the left, feedback list on the right -->
        <div class="flex flex-wrap lg:flex-nowrap gap-6">
            <!-- Feedback Form Section -->
            <div class="w-full lg:w-1/3 bg-white shadow-md rounded p-6">
                <h2 class="text-xl font-bold mb-4">Submit Feedback</h2>
                <form method="POST">
                    <div class="mb-4">
                        <label for="product_name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="product_name" id="product_name" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="rating" class="block text-sm font-medium text-gray-700">Rating (1-5)</label>
                        <select name="rating" id="rating" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Very Poor</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="feedback" class="block text-sm font-medium text-gray-700">Feedback</label>
                        <textarea name="feedback" id="feedback" rows="4" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 w-full">Submit Feedback</button>
                </form>
            </div>

            <!-- All Feedback Section -->
            <div class="w-full lg:w-2/3 bg-white shadow-md rounded p-6">
                <h2 class="text-xl font-bold mb-4">All Feedback</h2>
                <div class="overflow-y-auto max-h-96">
                    <table class="table-auto w-full">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2">#</th>
                                <th class="px-4 py-2">Product Name</th>
                                <th class="px-4 py-2">Rating</th>
                                <th class="px-4 py-2">Feedback</th>
                                <th class="px-4 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($feedback)): ?>
                                <?php foreach ($feedback as $index => $entry): ?>
                                    <tr class="border-t">
                                        <td class="px-4 py-2"><?php echo $index + 1; ?></td>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($entry['product_name']); ?></td>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($entry['rating']); ?></td>
                                        <td class="px-4 py-2">
                                            <?php echo strlen($entry['feedback']) > 50
                                                ? htmlspecialchars(substr($entry['feedback'], 0, 50)) . '...'
                                                : htmlspecialchars($entry['feedback']); ?>
                                        </td>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($entry['date']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">No feedback available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
