<?php
include "view/header.php";

$allowedPages = [
    'home.php',
    'view/login.php',
];

$requestedPage = $_GET['page'] ?? 'home.php';
$currentPage = in_array($requestedPage, $allowedPages, true) ? $requestedPage : 'home.php';
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
            <a class="nav-link <?php echo $currentPage === 'home.php' ? 'active' : ''; ?>" aria-current="page" href="?page=home.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $currentPage === 'view/login.php' ? 'active' : ''; ?>" aria-current="page" href="?page=view/login.php">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <?php if ($currentPage === 'home.php') { ?>
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
          <p class="text-white-75 mb-5">Manage books, borrowing, reservations, returns, fines and student accounts from one small PHP/MySQL system.</p>
          <a class="btn btn-primary btn-xl" href="?page=view/login.php">Get Started</a>
        </div>
      </div>
    </div>
  <?php } else {
    include __DIR__ . '/' . $currentPage;
  } ?>
</body>
