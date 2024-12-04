<?php
include "../../controller/Controller.php";
include "../../database/Database.php";

$db = new Database();
$pdo = $db->getConnection();


// Count all IDs across categories, participants, and programs
$sql = "SELECT COUNT(*) AS total_categories FROM categories";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$category_counts = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) AS total_participants FROM participants";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$participant_counts = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) AS total_programs FROM programs";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$program_counts = $stmt->fetch(PDO::FETCH_ASSOC);

// Calculate the total count of all records across the three tables
$total_records = $category_counts['total_categories'] + $participant_counts['total_participants'] + $program_counts['total_programs'];

// Count "ongoing" statuses across categories, participants, and programs
$sql = "SELECT COUNT(*) AS ongoing_categories FROM categories WHERE status = 'ongoing'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$ongoing_category_counts = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) AS ongoing_participants FROM participants WHERE status = 'ongoing'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$ongoing_participant_counts = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) AS ongoing_programs FROM programs WHERE status = 'ongoing'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$ongoing_program_counts = $stmt->fetch(PDO::FETCH_ASSOC);

// Calculate the total count of ongoing statuses across the three tables
$total_ongoing = $ongoing_category_counts['ongoing_categories'] + $ongoing_participant_counts['ongoing_participants'] + $ongoing_program_counts['ongoing_programs'];

// Count "complete" statuses across categories, participants, and programs
$sql = "SELECT COUNT(*) AS complete_categories FROM categories WHERE status = 'completed'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$complete_category_counts = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) AS complete_participants FROM participants WHERE status = 'completed'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$complete_participant_counts = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) AS complete_programs FROM programs WHERE status = 'completed'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$complete_program_counts = $stmt->fetch(PDO::FETCH_ASSOC);

// Calculate the total count of complete statuses across the three tables
$total_complete = $complete_category_counts['complete_categories'] + $complete_participant_counts['complete_participants'] + $complete_program_counts['complete_programs'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="bg-gray-100 text-gray-800">

 <!-- Header -->
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
<main class="container mx-auto px-4 py-6">
    <h2 class="text-4xl font-bold mb-8 text-center text-blue-600">Welcome to the Sangguniang Kabataan</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Total Count Section -->
        <section class="lg:col-span-1 flex flex-col space-y-4">
            <h3 class="text-2xl font-semibold mb-4 border-b pb-2 text-blue-600">Total Count of Program</h3>
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500">
                <p class="text-xl"><strong>Total Program:</strong> <?php echo $total_records; ?></p>
            </div>
        </section>

        <!-- Ongoing Status Section -->
        <section class="lg:col-span-1 flex flex-col space-y-4">
            <h3 class="text-2xl font-semibold mb-4 border-b pb-2 text-blue-600">Total Ongoing Status</h3>
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500">
                <p class="text-xl"><strong>Total Ongoing:</strong> <?php echo $total_ongoing; ?></p>
            </div>
        </section>

        <!-- Complete Status Section -->
        <section class="lg:col-span-1 flex flex-col space-y-4">
            <h3 class="text-2xl font-semibold mb-4 border-b pb-2 text-blue-600">Total Complete Status</h3>
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500">
                <p class="text-xl"><strong>Total Complete:</strong> <?php echo $total_complete; ?></p>
            </div>
        </section>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Bar Chart -->
        <section>
            <h3 class="text-2xl font-semibold mb-4 text-blue-600">Program Status Bar Chart</h3>
            <canvas id="barChart" class="w-full h-56"></canvas>  <!-- Bar chart with the same size as pie chart -->
        </section>

        <!-- Pie Chart -->
        <section>
            <h3 class="text-2xl font-semibold mb-4 text-blue-600">Program Status Pie Chart</h3>
            <div class="w-full h-64">
  <canvas id="pieChart" class="w-full h-full"></canvas>
</div>

        </section>
    </div>
</main>


<script>
    // Get the canvas elements
    const barChartCtx = document.getElementById('barChart').getContext('2d');
    const pieChartCtx = document.getElementById('pieChart').getContext('2d');

    // Data for the charts
    const totalCount = <?php echo $total_records; ?>;
    const ongoingCount = <?php echo $total_ongoing; ?>;
    const completeCount = <?php echo $total_complete; ?>;

    // Bar Chart Data
    const barChartData = {
        labels: ['Total Programs', 'Ongoing', 'Complete'],  // Labels for the bar chart
        datasets: [{
            label: 'Status Counts',
            data: [totalCount, ongoingCount, completeCount],  // Data values for the bar chart
            backgroundColor:  ['rgba(70, 130, 180)', 'rgba(65, 105, 225)', 'rgba(0, 0, 255)'],
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    // Pie Chart Data
    const pieChartData = {
        labels: ['Total Programs', 'Ongoing', 'Complete'],  // Labels for the pie chart
        datasets: [{
            data: [totalCount, ongoingCount, completeCount],  // Data values for the pie chart
            backgroundColor:['rgba(70, 130, 180)', 'rgba(65, 105, 225)', 'rgba(0, 0, 255)'],
            hoverOffset: 2
        }]
    };

    // Create the Bar Chart
    const barChart = new Chart(barChartCtx, {
        type: 'bar',  // Bar chart type
        data: barChartData,
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Create the Pie Chart
    const pieChart = new Chart(pieChartCtx, {
        type: 'pie',  // Pie chart type
        data: pieChartData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
    
     // Adjust size directly
  const canvas = document.getElementById('pieChart');
  canvas.width = 200; // width in pixels
  canvas.height = 200; // height in pixels
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
