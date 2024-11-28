<?php
require_once(__DIR__ . '../controller/Controller.php'); 
require_once(__DIR__ . '../database/database.php');
$database = new Database();
$pdo = $database->getConnection();

// Initialize UserManager with the PDO connection
$userManager = new Controller($pdo);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $userId = $userManager->register($name, $email, $password);
        if ($userId) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_type'] = 'user';

            // Redirect to the waiting area
            header('Location: waiting.php');
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed: ' . $e->getMessage()]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100" style="background-image: url('../assets/image/gist.jpg'); background-size: cover; background-position: center;">

<div class="min-h-screen flex items-center justify-center">
    <div class="flex bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl opacity-80">
        <div class="w-1/2 flex items-center justify-center border-r border-gray-300">
            <h2 class="text-2xl font-bold text-center">Welcome to Monitoring and evaluating SK programs of SK federation San Jose</h2>
        </div>
        <div class="w-1/2 p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Login to your account</h2>
            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold">Name</label>
                    <input type="name" name="name" id="name" placeholder="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-gray-800">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold">Email</label>
                    <input type="email" name="email" id="email" placeholder="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-gray-800">
                </div>
                <div class="mb-4 relative">
                    <label for="password" class="block text-gray-700 font-bold">Password</label>
                    <input type="password" name="password" id="password" placeholder="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-gray-800">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-5 cursor-pointer mt-6" onclick="togglePasswordVisibility()">
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </span>
                </div>
                <button type="submit" class="w-full bg-gray-800 text-white px-4 font-bold py-2 rounded-lg hover:bg-gray-700">Login</button>
            </form>
            <span class="text-gray-700">
    If you already Account, 
    <a href="signin.php" class="text-blue-500 hover:text-blue-700 font-semibold">Login Here!</a>
</span>
        </div>
    </div>
</div>
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const togglePasswordIcon = document.getElementById('togglePassword');
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        togglePasswordIcon.classList.toggle('fa-eye-slash');
    }
</script>
</body>
</html>