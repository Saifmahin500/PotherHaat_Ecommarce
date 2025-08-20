<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// BASE URL জেনারেশন আরও সুরক্ষিতভাবে
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$BASE = defined('BASE_URL') ? BASE_URL : "{$protocol}://{$host}/PHP/PHP_Projects/Ecommarce";

$isLoggedIn = !empty($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? 'Account';

// যদি HTML এ আউটপুট করা হয়, স্যানিটাইজ করা
$userName = htmlspecialchars($userName, ENT_QUOTES, 'UTF-8');
?>





<!-- Bootstrap 5 CSS & JS CDN  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Font Awesome CDN -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
  .navbar {
    margin-bottom: 20px;
  }


  body {
    padding-top: 58px;
  }

  /* Navbar Hover Animation */
  .navbar-nav .nav-link {
    position: relative;
    transition: color 0.3s ease;
    color: black;

  }

  .navbar-nav .nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    display: block;
    margin-top: 5px;
    right: 0;
    background: #db47dbff;
    transition: width 0.3s ease;
    -webkit-transition: width 0.3s ease;
  }

  .navbar-nav .nav-link:hover::after {
    width: 100%;
    left: 0;
    background: #140b02ff;
  }

  /* Active Link Highlight */
  .navbar-nav .active>.nav-link {
    color: #7B34E2 !important;
    font-weight: 500;
  }

  .navbar-brand i,
  .navbar-nav .nav-link i,
  .btn.btn-outline-light i,
  .navbar-nav .nav-link.position-relative i {
    color: #b448cfff !important;
  }
</style>

<nav class="navbar navbar-expand-lg shadow-sm fw-bold fixed-top" style="background-color: #E6E6FA;">
  <div class="container">
    <a class="navbar-brand fw-bold text-uppercase " style="color: #05010cff;" href="<?= $BASE ?>">
      <i class="fa-solid fa-shop me-2"></i> PotherHaat
    </a>
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNav"
      aria-controls="navbarNav"
      aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Left Menu -->
      <ul class="navbar-nav me-auto">
        <li class="nav-item active">
          <a class="nav-link" style="color: #7D3EE4;" href="<?= $BASE ?>">
            Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="<?= $BASE ?>/index.php?view=all">
            All Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="#">
            About Us
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="<?= $BASE ?>/contact_us.php">
            Contact Us
          </a>
        </li>
      </ul>

      <!-- Search Box -->
      <form class="d-flex me-3" role="search" action="search.php" method="get">
        <input
          class="form-control form-control-sm me-2"
          type="search"
          placeholder="Search products..."
          name="q"
          aria-label="Search" />
        <button class="btn btn-outline-light btn-sm" style="color: #7D3EE4;" type="submit">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
      </form>

      <!-- Cart & Auth Buttons -->
      <ul class="navbar-nav">
        <li class="nav-item me-3">
          <a class="nav-link position-relative " style="color: #7D3EE4;" href="cart.php">
            <i class="fa-solid fa-cart-shopping fs-5"></i>
            <span
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              3
            </span>
          </a>
        </li>
      </ul>

      <!-- login & registration -->

      <ul class="navbar-nav">
        <?php if ($isLoggedIn): ?>
          <li class="nav-item dropdown">
            <!-- User icon এবং username -->
            <a class="nav-link dropdown-toggle" href="#" id="accMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-user-circle fs-5"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="accMenu">
              <a class="dropdown-item" href="<?= $BASE ?>/auth/profile.php">
                <i class="fas fa-id-badge"></i> My Profile
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="<?= $BASE ?>/auth/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <button class="nav-link btn btn-outline-light mr-2" data-bs-toggle="modal" data-bs-target="#loginModal">
              <i class="fa-solid fa-right-to-bracket"></i> Login
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link btn btn-outline-warning mr-2" data-bs-toggle="modal" data-bs-target="#registerModal">
              <i class="fa-solid fa-right-to-bracket"></i> Register
            </button>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<?php if (!$isLoggedIn): ?>
  <!--Login Modal-->
  <div class="modal fade" id="loginModal">
    <div class="modal-dialog modal-dialog-centered">
      <form class="modal-content" method="post" action="<?= $BASE ?>/auth/login.php">
        <div class="modal-header">
          <h5 class="modal-title" id="loginTitle">Login to your account</h5>
          <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required autocomplete="email">
          </div>
          <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required autocomplete="current-password">
          </div>
          <small>
            <a href="<?= $BASE ?>/auth/fpass.php">Forgot Password?</a>
          </small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-dark">Login</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Register Modal -->
  <div class="modal fade" id="registerModal">
    <div class="modal-dialog modal-dialog-centered">
      <form class="modal-content" method="post" action="<?= $BASE ?>/auth/register.php">
        <div class="modal-header">
          <h5 class="modal-title" id="registerTitle">Create an Account</h5>
          <button type="button" class="close" data-bs-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" required autocomplete="username">
          </div>

          <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class=" form-control" required autocomplete="email">
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required autocomplete="new-password" minlength="6">
          </div>

          <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="cpassword" class="form-control" required autocomplete="new-password" minlength="6">
            <small class="text-muted">By creating an account, you agree to our terms.</small>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Register</button>
          </div>
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>