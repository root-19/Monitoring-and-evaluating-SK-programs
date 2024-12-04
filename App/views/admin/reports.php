<?php
include "../../controller/Controller.php";
include "../../database/database.php";

// Create an instance of the Database class to get the connection
$db = new Database();
$pdo = $db->getConnection(); // Initialize $pdo

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_report'])) {
    // Get POST data
    $summary = $_POST['summary'];
    $recommendation = $_POST['recommendation'];
    $conclusion = $_POST['conclusion'];
    $report_date = $_POST['report_date'];

    // Validate data
    if (empty($summary) || empty($recommendation) || empty($conclusion) || empty($report_date)) {
        $error = "All fields are required.";
    } else {
        // Prepare and execute the SQL query to insert the report
        $query = "INSERT INTO report (summary, recommendation, conclusion, report_date) 
                  VALUES (:summary, :recommendation, :conclusion, :report_date)";
        $stmt = $pdo->prepare($query);

        try {
            $stmt->execute([
                ':summary' => $summary,
                ':recommendation' => $recommendation,
                ':conclusion' => $conclusion,
                ':report_date' => $report_date
            ]);
            $success = "Report created successfully!";
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Fetch all reports from the database
$sql = "SELECT id, summary, recommendation, conclusion, report_date, created_at FROM report ORDER BY created_at DESC";
$reports = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create & View Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <!-- Form for creating a new report -->
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Create New Report</h1>
        
        <form action="" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="summary" class="block text-sm font-semibold text-gray-700">Summary</label>
                <textarea id="summary" name="summary" rows="4" required class="w-full p-2 border border-gray-300 rounded-md"></textarea>
            </div>
            
            <div class="mb-4">
                <label for="recommendation" class="block text-sm font-semibold text-gray-700">Recommendation</label>
                <textarea id="recommendation" name="recommendation" rows="4" required class="w-full p-2 border border-gray-300 rounded-md"></textarea>
            </div>
            
            <div class="mb-4">
                <label for="conclusion" class="block text-sm font-semibold text-gray-700">Conclusion</label>
                <textarea id="conclusion" name="conclusion" rows="4" required class="w-full p-2 border border-gray-300 rounded-md"></textarea>
            </div>
            
            <!-- <div class="mb-4"> -->
                <label for="report_date" name="report_date"  class="block text-sm font-semibold text-gray-700 hidden">Report Date</label>
                <!-- <input type="date" id="report_date" name="report_date" required class="w-full p-2 border border-gray-300 rounded-md">
            </div> -->
            
            <button type="submit" name="create_report" class="w-full p-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Submit Report
            </button>
        </form>
    </div>

    <!-- Display existing reports -->
   <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Generated Reports</h2>

    <div class="overflow-x-auto">
        <table class="table-auto w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Summary</th>
                    <th class="px-4 py-2">Recommendation</th>
                    <th class="px-4 py-2">Conclusion</th>
                    <th class="px-4 py-2">Report Date</th>
                    <th class="px-4 py-2">Created At</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
        </table>

        <!-- Table body with scrollable content -->
        <div class="max-h-96 overflow-y-auto">
            <table class="table-auto w-full bg-white shadow-md rounded-lg">
                <tbody>
                    <?php if (!empty($reports)): ?>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($report['id']); ?></td>
                                <td class="border px-4 py-2">
                                    <?php echo strlen($report['summary']) > 10 
                                        ? htmlspecialchars(substr($report['summary'], 0, 10)) . '...' 
                                        : htmlspecialchars($report['summary']); ?>
                                    <?php if (strlen($report['summary']) > 10): ?>
                                        <button onclick="toggleText('summary-<?php echo $report['id']; ?>')" class="text-blue-500 hover:underline">
                                            See More
                                        </button>
                                        <span id="summary-<?php echo $report['id']; ?>" class="hidden"><?php echo htmlspecialchars($report['summary']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <?php echo strlen($report['recommendation']) > 10 
                                        ? htmlspecialchars(substr($report['recommendation'], 0, 10)) . '...' 
                                        : htmlspecialchars($report['recommendation']); ?>
                                    <?php if (strlen($report['recommendation']) > 10): ?>
                                        <button onclick="toggleText('recommendation-<?php echo $report['id']; ?>')" class="text-blue-500 hover:underline">
                                            See More
                                        </button>
                                        <span id="recommendation-<?php echo $report['id']; ?>" class="hidden"><?php echo htmlspecialchars($report['recommendation']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <?php echo strlen($report['conclusion']) > 10 
                                        ? htmlspecialchars(substr($report['conclusion'], 0, 10)) . '...' 
                                        : htmlspecialchars($report['conclusion']); ?>
                                    <?php if (strlen($report['conclusion']) > 10): ?>
                                        <button onclick="toggleText('conclusion-<?php echo $report['id']; ?>')" class="text-blue-500 hover:underline">
                                            See More
                                        </button>
                                        <span id="conclusion-<?php echo $report['id']; ?>" class="hidden"><?php echo htmlspecialchars($report['conclusion']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($report['report_date']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($report['created_at']); ?></td>
                                <td class="border px-4 py-2">
                                    <button onclick="downloadReportToPDF(<?php echo htmlspecialchars(json_encode($report)); ?>)" 
                                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                        Download PDF
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">No reports available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

<script>
    function toggleText(id) {
        const span = document.getElementById(id);
        span.classList.toggle('hidden');
    }

    function downloadReportToPDF(report) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Add title and report data
        doc.text("Report Details", 14, 10);
        doc.text(`ID: ${report.id}`, 14, 20);
        doc.text(`Summary: ${report.summary}`, 14, 30);
        doc.text(`Recommendation: ${report.recommendation}`, 14, 40);
        doc.text(`Conclusion: ${report.conclusion}`, 14, 50);
        doc.text(`Report Date: ${report.report_date}`, 14, 60);
        doc.text(`Created At: ${report.created_at}`, 14, 70);

        // Save the PDF
        doc.save(`report_${report.id}.pdf`);
    }
</script>


</body>
</html>


<script>
    function toggleText(id) {
        const span = document.getElementById(id);
        if (span.classList.contains('hidden')) {
            span.classList.remove('hidden');
        } else {
            span.classList.add('hidden');
        }
    }
</script>