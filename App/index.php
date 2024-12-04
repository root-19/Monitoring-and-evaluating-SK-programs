<?php
include "./controller/Controller.php";
include "./database/Database.php";

$db = new Database();
$pdo = $db->getConnection();

// Fetch categories
$sql = "SELECT * FROM categories ORDER BY date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch participants
$sql = "SELECT * FROM participants ORDER BY date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch programs
$sql = "SELECT * FROM programs ORDER BY date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch feedback
$sql = "SELECT * FROM feedback ORDER BY date DESC";
$stmt = $pdo->query($sql);
$feedback = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">


    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-md">
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
    <a href="signin.php" 
       class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
       Login
    </a>
</li>

            </ul>
        </nav>
    </div>
</header>

  
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        <h2 class="text-3xl font-bold mb-8 text-center">Welcome to the Sangguniang Kabataan</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Categories Section -->
            <section class="lg:col-span-1">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Categories</h3>
                <div class="space-y-4">
                    <?php foreach ($categories as $category): ?>
                        <div class="bg-white shadow-md rounded-lg p-4 border hover:shadow-lg transition">
                            <h4 class="text-lg font-semibold mb-1"><?php echo htmlspecialchars($category['title']); ?></h4>
                            <p class="text-sm text-gray-600"> <strong>Message: </strong><?php echo htmlspecialchars($category['message']); ?></p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-sm <?php echo ($category['status'] === 'active') ? 'text-green-600' : 'text-red-600'; ?>">
                                <strong class="text-black">Status: </strong> <?php echo ucfirst($category['status']); ?>
                                </span>
                                <span class="text-xs text-gray-500">
                                <strong>Date: </strong> <?php echo date('F j, Y', strtotime($category['date'])); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Participants Section -->
            <section class="lg:col-span-1">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Participants</h3>
                <div class="space-y-4">
                    <?php foreach ($participants as $participant): ?>
                        <div class="bg-white shadow-md rounded-lg p-4 border hover:shadow-lg transition">
                            <h4 class="text-lg font-semibold mb-1"><?php echo htmlspecialchars($participant['name']); ?></h4>
                            <p class="text-sm text-gray-600">
                                <strong>Program:</strong> <?php echo htmlspecialchars($participant['program']); ?>
                            </p>
                            <p class="text-sm">
                                <strong>Status:</strong> 
                                <span class="<?php echo ($participant['status'] === 'active') ? 'text-green-600' : 'text-red-600'; ?>">
                                    <?php echo ucfirst($participant['status']); ?>
                                </span>
                            </p>
                            <p class="text-xs text-gray-500">
                                <strong>Date:</strong> <?php echo date('F j, Y', strtotime($participant['date'])); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="lg:col-span-1">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Programs</h3>
                <div class="space-y-4">
                    <?php foreach ($programs as $program): ?>
                        <div class="bg-white shadow-md rounded-lg p-4 border hover:shadow-lg transition">
                            <h4 class="text-lg font-semibold mb-1"><?php echo htmlspecialchars($program['name']); ?></h4>
                            <p class="text-sm text-gray-600">
                                <strong>Program:</strong> <?php echo htmlspecialchars($program['program']); ?>
                            </p>
                            <p class="text-sm">
                                <strong>Status:</strong> 
                                <span class="<?php echo ($program['status'] === 'active') ? 'text-green-600' : 'text-red-600'; ?>">
                                    <?php echo ucfirst($program['status']); ?>
                                </span>
                            </p>
                            <p class="text-xs text-gray-500">
                                <strong>Date:</strong> <?php echo date('F j, Y', strtotime($program['date'])); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="lg:col-span-1">
    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Feedback</h3>
    <div class="space-y-4">
        <?php if (!empty($feedback)): ?>
            <?php foreach ($feedback as $entry): ?>
                <div class="bg-white shadow-md rounded-lg p-4 border hover:shadow-lg transition">
                    <h4 class="text-lg font-semibold mb-1"><?php echo htmlspecialchars($entry['product_name']); ?></h4>
                    <div class="flex items-center mb-2">
                        <strong class="text-sm text-gray-600 mr-2">Rating:</strong>
                        <?php
                        $rating = intval($entry['rating']); // Ensure rating is an integer
                        for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $rating): ?>
                                <!-- Filled star -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.164c.969 0 1.371 1.24.588 1.81l-3.37 2.452a1 1 0 00-.364 1.118l1.287 3.956c.3.922-.755 1.688-1.538 1.118L10 13.011a1 1 0 00-1.175 0l-3.37 2.451c-.783.57-1.838-.196-1.538-1.118l1.287-3.956a1 1 0 00-.364-1.118L1.77 9.384c-.783-.57-.38-1.81.588-1.81h4.164a1 1 0 00.95-.69l1.286-3.957z" />
                                </svg>
                            <?php else: ?>
                                <!-- Empty star -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.164c.969 0 1.371 1.24.588 1.81l-3.37 2.452a1 1 0 00-.364 1.118l1.287 3.956c.3.922-.755 1.688-1.538 1.118L10 13.011a1 1 0 00-1.175 0l-3.37 2.451c-.783.57-1.838-.196-1.538-1.118l1.287-3.956a1 1 0 00-.364-1.118L1.77 9.384c-.783-.57-.38-1.81.588-1.81h4.164a1 1 0 00.95-.69l1.286-3.957z" />
                                </svg>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <p class="text-sm text-gray-600">
                        <strong>Feedback:</strong> <?php echo strlen($entry['feedback']) > 50 
                            ? htmlspecialchars(substr($entry['feedback'], 0, 50)) . '...' 
                            : htmlspecialchars($entry['feedback']); ?>
                    </p>
                    <p class="text-xs text-gray-500">
                        <?php echo date('F j, Y', strtotime($entry['date'])); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-sm text-gray-600 text-center">No feedback available.</p>
        <?php endif; ?>
    </div>
</section>

    </main>
</body>
</html>