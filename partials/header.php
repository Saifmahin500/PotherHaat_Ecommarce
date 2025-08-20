<?php
require_once __DIR__ . '/../admin/dbConfig.php';
?>

<!DOCTYPE html>
<html>

<head>
	<title>My Product Store</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" integrity="sha512-rt/SrQ4UNIaGfDyEXZtNcyWvQeOq0QLygHluFQcSjaGB04IxWhal71tKuzP6K8eYXYB6vJV4pHkXcmFGGQ1/0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link rel="stylesheet" type="text/css" href="asstes/css/style.css">

	<style>
		body {
			background: #f7f8fa;
		}

		.main-wrap {
			display: flex;
		}

		.left-sidebar {
			width: 260px;
			min-width: 260px;
			background: #fff;
			border-right: 1px solid #e9ecef;
		}

		.content-area {
			flex: 1;
		}

		.cat-item {
			display: flex;
			align-items: center;
			margin-bottom: 8px;
		}

		.cat-item input {
			margin-right: 8px;
		}

		.product-card {
			background: #fff;
			transition: all 0.3s ease;
			border-radius: 12px;
			overflow: hidden;
		}

		.product-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
		}

		.product-img {
			width: 100%;
			height: 220px;
			overflow: hidden;
			display: flex;
			align-items: center;
			justify-content: center;
			background: #f8f9fa;
		}

		.product-img img {
			max-width: 100%;
			max-height: 100%;
			object-fit: contain;
			transition: transform 0.3s ease;
		}

		.product-card:hover .product-img img {
			transform: scale(1.05);
		}



		.sticky-sidebar {
			position: sticky;
			top: 20px;
		}
	</style>
</head>

<body>

</body>

</html>