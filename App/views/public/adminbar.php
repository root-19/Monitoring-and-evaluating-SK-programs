<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>admin</title>
</head>
<style>
.win{
    width: 150px;
    margin-left: 10px;
}
</style>
<body class="bg-gray-100">

  <!-- Main Container -->
  <div class="flex h-screen">
    <!-- Mobile Toggle Button -->
    <button 
      id="toggleSidebar" 
      class="md:hidden absolute top-4 left-4 z-50 p-2 bg-blue-700 text-white rounded-md focus:outline-none"
    >
      <svg 
        id="menuIcon" 
        xmlns="http://www.w3.org/2000/svg" 
        class="h-6 w-6" 
        fill="none" 
        viewBox="0 0 24 24" 
        stroke="currentColor"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
      </svg>
      <svg 
        id="closeIcon" 
        xmlns="http://www.w3.org/2000/svg" 
        class="hidden h-6 w-6" 
        fill="none" 
        viewBox="0 0 24 24" 
        stroke="currentColor"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Sidebar -->
    <div 
      id="sidebar" 
      class="bg-blue-700 text-white w-74 md:flex flex-col p-8 space-y-8 hidden md:block"
    >
      <!-- Logo Section -->
      <div class="flex items-center space-x-3">
        <img src="../../assets/image/skl.jpg" alt="Logo"  class="win rounded-full">
        <!-- <h1 class="text-2xl font-bold">MyLogo</h1> -->
      </div>

      <!-- Navigation Links -->
      <nav>
        <ul class="space-y-4">
          <li>
            <a href="../admin/index.php" class="block py-2 px-4 rounded-md hover:bg-blue-500">Home</a>
          </li>
          
            <a href="../admin/program.php" class="block py-2 px-4 rounded-md hover:bg-blue-500">Programs</a>
          </li>
          <li>
            <a href="../admin/category.php" class="block py-2 px-4 rounded-md hover:bg-blue-500">Category</a>
          </li>
          <li>
            <a href="../admin/participants.php" class="block py-2 px-4 rounded-md hover:bg-blue-500">participants</a>
          </li>
          <li>
            <a href="../admin/feedback.php" class="block py-2 px-4 rounded-md hover:bg-blue-500">feedback</a>
          </li>
          <li>
            <a href="../admin/reports.php" class="block py-2 px-4 rounded-md hover:bg-blue-500">report</a>
          </li>
          <li>
            <a href="../admin/logout.php" class="block py-2 px-4 rounded-md hover:bg-blue-500">Logout</a>
          </li>
        </ul>
      </nav>
    </div>

   

  <!-- JavaScript for Toggle -->
  <script>
    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const menuIcon = document.getElementById('menuIcon');
    const closeIcon = document.getElementById('closeIcon');

    toggleSidebar.addEventListener('click', () => {
      sidebar.classList.toggle('hidden'); // Show/hide sidebar
      menuIcon.classList.toggle('hidden'); // Toggle menu icon
      closeIcon.classList.toggle('hidden'); // Toggle close icon
    });
  </script>
</body>
</html>

