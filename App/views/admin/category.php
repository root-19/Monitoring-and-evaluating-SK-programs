<?php
include "../../controller/Controller.php";
include "../../database/Database.php";

// Create an instance of the Database class to get the connection
$db = new Database();
$pdo = $db->getConnection();  // Now $pdo is correctly initialized

// Fetch existing categories
$sql = "SELECT * FROM categories ORDER BY date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
</head>
<?php include "../public/adminbar.php"; ?>

<body class="bg-gray-100">

    <!-- Form for creating a new category -->
    <div class="container ml-20 ">
        <h1 class="text-3xl font-bold mb-4">Create New Category</h1>
        
        <form id="createCategoryForm" class="bg-white p-4 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="title" class="block text-sm font-semibold text-gray-700">Category Title</label>
                <input type="text" id="title" name="title" required class="w-full p-2 border border-gray-300 rounded-md">
            </div>
            
            <div class="mb-4">
                <label for="message" class="block text-sm font-semibold text-gray-700">Category Message</label>
                <textarea id="message" name="message" required class="w-full p-2 border border-gray-300 rounded-md" rows="4"></textarea>
            </div>
            
            <div class="mb-4">
                <label for="status" class="block text-sm font-semibold text-gray-700">Status</label>
                <select id="status" name="status" required class="w-full p-2 border border-gray-300 rounded-md">
                    <option value="pending">Pending</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="done">Done</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="date" class="block text-sm font-semibold text-gray-700">Date</label>
                <input type="date" id="date" name="date" required class="w-full p-2 border border-gray-300 rounded-md">
            </div>
            
            <button type="submit" id="createCategoryBtn" class="w-full p-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Create Category
            </button>
        </form>
    </div>

    <!-- Display Categories -->
    <div class="container mx-auto p-4 mt-6">
        <h2 class="text-2xl font-semibold mb-4">Existing Categories</h2>
        
        <table class="w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="p-3 text-left bg-gray-200">Title</th>
                    <th class="p-3 text-left bg-gray-200">Message</th>
                    <th class="p-3 text-left bg-gray-200">Status</th>
                    <th class="p-3 text-left bg-gray-200">Date</th>
                </tr>
            </thead>
            <tbody id="categoryTable">
                <?php foreach ($categories as $category): ?>
                    <tr class="border-b">
                        <td class="p-3"><?php echo htmlspecialchars($category['title']); ?></td>
                        <td class="p-3"><?php echo htmlspecialchars($category['message']); ?></td>
                        <td class="p-3"><?php echo ucfirst($category['status']); ?></td>
                        <td class="p-3"><?php echo date('F j, Y', strtotime($category['date'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- AJAX Script for Form Submission -->
    <script>
        $(document).ready(function() {
            $('#createCategoryForm').on('submit', function(e) {
                e.preventDefault();  // Prevent default form submission
                
                // Get form data
                var formData = $(this).serialize();
                
                // Send AJAX request
                $.ajax({
                    url: '../../model/categories.php',  // The PHP script handling the form submission
                    type: 'POST',
                    data: formData + '&create_category=true', // Include the flag to identify the request
                    success: function(response) {
                        alert('Category created successfully!');
                        
                        // Optionally, refresh the table content without refreshing the page
                        $('#categoryTable').append(response);  // Append new category row
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>

</body>
</html>
