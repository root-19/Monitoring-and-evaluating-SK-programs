<?php
include "../../controller/Controller.php";
include "../../database/Database.php";

// Create an instance of the Database class to get the connection
$db = new Database();
$pdo = $db->getConnection(); 

// Fetch existing categories
$sql = "SELECT * FROM categories ORDER BY date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Handle deletion request
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    // Prepare the DELETE query
    $sql = "DELETE FROM categories WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Bind the participant ID to the query
    $stmt->bindParam(':id', $deleteId);

    // Execute the deletion query
    if ($stmt->execute()) {
        // Redirect to the same page after successful deletion
        header("Location: category.php");
        exit();
    } else {
        // If there's an error, display a message
        echo "Error deleting participant.";
    }
}
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
                    <option value="completed">Completed</option>
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

    <div class="container mx-auto p-4 mt-6">
    <h2 class="text-2xl font-semibold mb-4">Existing Categories</h2>
    
    <table class="w-full bg-white shadow-md rounded-lg">
        <thead>
            <tr>
                <th class="p-3 text-left bg-gray-200">Title</th>
                <th class="p-3 text-left bg-gray-200">Message</th>
                <th class="p-3 text-left bg-gray-200">Status</th>
                <th class="p-3 text-left bg-gray-200">Date</th>
                <th class="p-3 text-left bg-gray-200">Actions</th>
            </tr>
        </thead>
        <tbody id="categoryTable">
            <?php foreach ($categories as $category): ?>
                <tr class="border-b">
                    <td class="p-3"><?php echo htmlspecialchars($category['title']); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($category['message']); ?></td>
                    <td class="p-3">
                        <form method="POST" action="../../model/update_status.php" class="flex items-center">
                            <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                            <select name="status" class="border rounded px-2 py-1" onchange="this.form.submit()">
                                <option value="pending" <?php echo $category['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="ongoing" <?php echo $category['status'] === 'ongoing' ? 'selected' : ''; ?>>Ongoing</option>
                                <option value="completed" <?php echo $category['status'] === 'completed' ? 'selected' : ''; ?>>compeleted</option>
                            </select>
                        </form>
                    </td>
                    <td class="p-3"><?php echo date('F j, Y', strtotime($category['date'])); ?></td>
                    <td class="p-3">

                    
    <a href="category.php?delete_id=<?php echo $category['id']; ?>" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</a>
</td>

                    </td>
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
                    url: '../../model/categories.php', 
                    type: 'POST',
                    data: formData + '&create_category=true',
                    success: function(response) {
                        alert('Category created successfully!');
                        
                    
                        $('#categoryTable').append(response); 
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
