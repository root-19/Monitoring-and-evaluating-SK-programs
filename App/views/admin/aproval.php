<?php
include "../../controller/Controller.php";
include "../../database/Database.php";

// Create an instance of the Database class to get the connection
$db = new Database();
$pdo = $db->getConnection(); 

// Fetch unapproved users
$sql = "SELECT id, name, email FROM users WHERE approved = 0";
$stmt = $pdo->query($sql);
$pendingUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Approve user
if (isset($_POST['approve'])) {
    $userId = $_POST['user_id'];
    $sql = "UPDATE users SET approved = 1 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    header('Location: aproval.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve admins</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<header class="bg-blue-700 text-white shadow-md">
    <div class="container mx-auto flex items-center justify-between px-4 py-4">
        <!-- Logo and Title -->
        <div class="flex items-center space-x-4">
            <img src="../../assets/image/sks.png" alt="Logo" class="w-12 h-13 object-cover rounded">
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
<div class="flex items-center justify-center">
    <div class="container mx-auto p-6 bg-white rounded-lg ml-80 mb-80 shadow-md">
        <table class="min-w-full table-auto bg-gray-100 border-collapse border border-gray-200 rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($pendingUsers as $user): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo $user['id']; ?></td>
                    <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($user['name']); ?></td>
                    <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td class="py-3 px-6 text-center">
                        <form method="POST" action="aproval.php">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" name="approve" class="bg-blue-700 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600">
                                Approve
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


</body>
</html>
