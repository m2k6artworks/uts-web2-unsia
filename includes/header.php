<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all">
    <title>Java Technic Inventory Management</title>
    
    <link rel="apple-touch-icon" href="assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="assets/images/icons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="assets/images/icons/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="assets/images/icons/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="assets/images/icons/favicon-16x16.png" sizes="16x16">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:url" content="https://javatechnic.com">
    <meta property="og:title" content="Sandbox Java Technic Inventory Management">
    <meta property="og:description" content="Sandbox Inventory management system for Java Technic to track and manage equipment, tools, and supplies.">
    <meta property="og:image" content="assets/images/logo-long.png">
    <meta property="og:site_name" content="Sandbox Java Technic Inventory Management">
    <meta property="og:ttl" content="3600">
    <meta property="og:type" content="website">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.bootstrap5.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-sticky top-0 z-1">
        <div class="container-fluid">
            <div class="col-auto me-auto">
                <a class="navbar-brand" href="./">
                    <img src="assets/images/logo-long.png" alt="Java Technic Logo" height="40">
                </a>
            </div>
            <div class="col-auto">
                <!-- Dark Mode Toggle -->
                <button class="btn dark-mode-toggle" id="darkModeToggle">
                    <i class="fas fa-sun text-warning"></i>
                    <span class="text-light d-none d-sm-inline">Light Mode</span>
                </button>
            </div>
            <div class="col-auto">
                <div class="navbar-nav ms-auto d-flex align-items-center">    
                    <!-- Profile Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle fa-lg me-1"></i> admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end position-absolute" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4"> 