<div class="sidebar">
	<div class="text-center mb-3">
		<?php
		// Start the session if it’s not already started
		if (!isset($_SESSION)) session_start();

		// Include database configuration file
		require_once __DIR__ . '/../dbConfig.php';

		// Get the currently logged-in admin ID from session
		$admin_id = $_SESSION['admin_id'] ?? null;


		// Default values for admin photo and name
		$adminPhoto = 'default.jpg';
		$adminName = 'Admin';

		// If admin is logged in, fetch admin details from DB
		if ($admin_id) {
			// Prepare SQL query to fetch admin info
			$stmt = $DB_con->prepare("SELECT * FROM admins WHERE id =?");
			$stmt->execute([$admin_id]);

			// Fetch admin row
			$admin = $stmt->fetch(PDO::FETCH_ASSOC);

			// If admin exists, use their photo and username
			if ($admin) {
				$adminPhoto = !empty($admin['photo']) ? $admin['photo'] : 'default.jpg';
				$adminName = $admin['username'];
			}
		}
		?>

		<!-- Admin profile photo -->
		<img src="../uploads/admins/<?= htmlspecialchars($adminPhoto) ?>" alt="Profile" class="rounded-circle" width="80" height="80">



		<!-- Admin username -->
		<h5 class="mt-2"><?= htmlspecialchars($adminName) ?></h5>
	</div>

	<!-- Sidebar title -->
	<h4><i class="fas fa-user-cog"></i> Admin Panel</h4>

	<!-- Dashboard link -->
	<a href="index.php?page=dashboard">
		<i class="fas fa-tachometer-alt"></i> Dashboard
	</a>

	<!-- All products link -->
	<a href="index.php?page=products">
		<i class="fas fa-box-open"></i> All Products
	</a>

	<!-- Categories link -->
	<a href="index.php?page=categories">
		<i class="fas fa-th-list"></i> All Categories
	</a>

	<!-- Attributes link -->
	<a href="index.php?page=attributes">
		<i class="fas fa-tags"></i> Attributes
	</a>

	<!-- Admin profile link -->
	<a href="index.php?page=admin_profile">
		<i class="fas fa-user"></i> Change Profile
	</a>

	<!-- Inventory submenu with collapse -->
	<a href="#inventorySubmenu" data-toggle="collapse" class="dropdown-toggle">
		<i class="fas fa-warehouse"></i> Inventory
	</a>

	<!-- Inventory submenu items -->
	<div class="collapse" id="inventorySubmenu" style="margin-left: 10px;">
		<a href="index.php?page=stock_in">
			<i class="fas fa-arrow-down"></i> Stock In
		</a>
		<a href="index.php?page=stock_out">
			<i class="fas fa-arrow-up"></i> Stock Out
		</a>
		<a href="index.php?page=stock_by_products">
			<i class="fas fa-boxes"></i> Stock By Products
		</a>
		<a href="index.php?page=inventory_report">
			<i class="fas fa-chart-bar"></i> Report
		</a>
	</div>

	<!-- user feedback -->
	<a href="index.php?page=feedback">
		<i class="fas fa-user"></i> User feedback
		<span id="fbcount" class="badge badge-pill badge-danger" style="margin-left: 6px;">0</span>
	</a>

	<!-- Link to view main website -->
	<a href="../index.php" style="color: white; text-decoration: none;">
		<i class="fa-solid fa-eye mr-2"></i> View website
	</a>

	<!-- Logout link -->
	<a href="logout.php">
		<i class="fas fa-sign-out-alt"></i> Logout
	</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
	(function pollFedback() {
		$.ajax({
			url: 'ajax/send_reply.php',
			method: 'GET',
			dataType: 'json',

		}).done(function(d) {
			$('#fbcount').text((d && d.count) ? d.count : 0);
		}).always(function(d) {
			setTimeout(pollFedback, 10000)
		})
	})();
</script>