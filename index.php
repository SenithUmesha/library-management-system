<?php
include "includes/header_new.php";

$current_page = isset($_GET['page']) ? $_GET['page'] : 'home.php';
?>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Library Management System</span>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'home.php' ? 'active' : ''; ?>" aria-current="page" href="?page=home.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'login.php' ? 'active' : ''; ?>" aria-current="page" href="?page=login.php">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <?php
  if ($current_page == 'home.php') {
  ?>
    <style>
      body {
        padding: 0;
        margin: 0;
        width: 100%;
      }
    </style>

    <div class="container px-4 px-lg-5 h-100" style="padding-top: 80px;">
      <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
        <div class="col-lg-8 align-self-end">
          <h1 class="text-black font-weight-bold">Welcome to Our Library Management System</h1>
          <h5 class="text-black" style="padding-top: 20px;">Ananda College - Galle</h5>
          <hr class="divider" />
        </div>
        <div class="col-lg-8 align-self-baseline">
          <p class="text-white-75 mb-5">Efficiently manage your library resources with our user-friendly platform. Explore a world of organized information and seamless book transactions.</p>
          <a class="btn btn-primary btn-xl" href="?page=login.php" id="getStartedBtn">Get Started</a>
        </div>
      </div>
    </div>

    <script>
      document.getElementById('getStartedBtn').addEventListener('click', function() {
        window.location.href = 'login.php';
      });
    </script>
  <?php
  } else {
    include $current_page;
  }
  ?>
</body>