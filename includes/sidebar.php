<?php
  require('session.php');
  confirm_logged_in();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <style type="text/css">
    #overlay {
      position: fixed;
      display: none;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(92, 143, 20, 0.54);
      z-index: 2;
      cursor: pointer;
    }
    #text{
      position: absolute;
      top: 50%;
      left: 50%;
      font-size: 50px;
      color: white;
      transform: translate(-50%,-50%);
      -ms-transform: translate(-50%,-50%);
    }
    
    /* New styles for floating sidebar */
    #accordionSidebar {
      position: fixed;
      height: 50vh;
      
      z-index: 1000;
      transition: all 0.3s;
    }
    
    /* Adjust main content when sidebar is toggled */
    .sidebar-toggled #accordionSidebar {
      width: 9.5rem;
    }
    
    /* Remove bottom padding to eliminate dead space */
    .sidebar-nav {
      padding-bottom: 0 !important;
    }
    
    /* Add padding to main content to prevent overlap with fixed sidebar */
    #content {
      padding-left: 225px;
      transition: padding-left 0.3s;
    }
    
    .sidebar-toggled #content {
      padding-left: 3.5rem;
    }
    
    /* Enhanced menu item styles with box and color effects */
    /* Adjust the nav-item link padding and margins */
/* Adjust nav-item link hover and active states to match sidebar color */
.nav-item .nav-link {
    border-radius: 5px;
    margin: 0 10px 0px 1px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    margin-bottom: 10px;
}

.nav-item .nav-link:hover {
    background-color:hsl(165, 72.50%, 10.00%);  /* Match the sidebar's background color */
    box-shadow: 0 0 0px rgba(0, 0, 0, 0.0);
    transform: translateX(2px);
}

.nav-item .nav-link.active {
    background-color:hsl(0, 11.10%, 14.10%);  /* Match the sidebar's background color */
    color: white;
    box-shadow: 0 0 0px rgba(0, 0, 0, 0.0);
}

.nav-item .nav-link:active {
    background-color: #4B2E2E;  /* Match the sidebar's background color */
    box-shadow: inset 0 0 0px rgba(0, 0, 0, 0.0);
}
    
    /* Adjust mobile view */
    @media (max-width: 768px) {
      #accordionSidebar {
        position: absolute;
        transform: translateX(-100%);
      }
      
      .sidebar-toggled #accordionSidebar {
        transform: translateX(0);
      }
      
      #content {
        padding-left: 0;
      }
      
      .nav-item .nav-link {
        margin: 0 5px 5px 5px;
      }
    }
  </style>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title> TAKEOUTCOFFEE </title>
  <link rel="icon" href="">

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
          
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #4B2E2E;">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-14">
          <i class="fas fa-coffee"></i>
        </div>
        <div class="sidebar-brand-text mx-2 text-white">TAKEOUTCOFFEE</div>
      </a>

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-home"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Tables Buttons -->
      <li class="nav-item">
        <a class="nav-link" href="transaction.php">
          <i class="fas fa-fw fa-cogs"></i>
          <span>Orders History</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="pro_ingredients.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Ingredients</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="pro_recipe.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Recipes</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="product.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Products</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="category.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Category</span></a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="supplier.php">
          <i class="fas fa-fw fa-cogs"></i>
          <span>Supplier</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="user.php">
          <i class="fas fa-fw fa-users"></i>
          <span>Accounts</span></a>
      </li>

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline mt-3">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->
    <?php include_once 'topbar.php'; ?>
    
    <!-- JavaScript to add active class to current page menu item -->
    <script>
      // Add this script just before the closing body tag
      document.addEventListener('DOMContentLoaded', function() {
        // Get current page URL
        const currentLocation = window.location.href;
        
        // Find all nav links
        const navLinks = document.querySelectorAll('.nav-item .nav-link');
        
        // Check each link against current URL
        navLinks.forEach(link => {
          if (currentLocation.includes(link.getAttribute('href'))) {
            link.classList.add('active');
          }
          
          // Add click effect
          link.addEventListener('click', function() {
            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active-temp'));
            
            // Add temporary active class to simulate click effect
            this.classList.add('active-temp');
          });
        });
      });
    </script>
  
