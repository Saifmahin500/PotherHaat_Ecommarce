<?php

if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

require_once __DIR__ . '/admin/dbConfig.php';

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$BASE = defined('BASE_URL') ? BASE_URL : "{$protocol}://{$host}/PHP/Ecommarce";

$userId = (int)$_SESSION['user_id'];
$st = $DB_con->prepare("SELECT id FROM carts WHERE user_id = ? AND status = 'open' LIMIT 1");
$st->execute([$userId]);
$cartId = ($st->fetch(PDO::FETCH_ASSOC)['id'] ?? null);

$items = [];
$grand = 0.00;

if ($cartId) {
    $sql = "SELECT ci.id AS item_id, ci.product_id, ci.qty, ci.unit_price, p.product_name, p.product_image FROM cart_items ci JOIN products p ON p.id = ci.product_id WHERE ci.product_id = ?";
    $st = $DB_con->prepare($sql);
    $st->execute([$cartId]);
    $items = $st->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $it) {
        $grand += ((float)$it['unit_price'] * (int)$it['qty']);
    }
}

if (file_exists(__DIR__ . '/partials/header.php')) include __DIR__ . '/partials/header.php';
if (file_exists(__DIR__ . '/partials/navbar.php')) include __DIR__ . '/partials/navbar.php';

?>

<!DOCTYPE html>
<html>
<head>
	<?php if( !defined('BOOTSTRAP_LOADED')): ?>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<?php endif; ?>
	<title>Products Cart</title>
	<style>
		.cart-page { padding: 24px 0; }
		.cart-card { border: 1px solid #e9ecef; border-radius: .5rem; overflow: hidden; }
		.cart-head { background: #f8f9fa; padding: .75rem 1rem; font-weight: 600; }
		.cart-img { width: 64px; height: 64px; object-fit: cover; border-radius: .25rem; }
		.qty { width: 96px; }
		.summary-card { position: sticky; top: 16px; }
		.empty-card { border: 1px dashedrgb(3, 54, 105); }
		.table td, .table th { vertical-align: middle; }
		.remove { width: 36px; height: 36px; line-height: 1; }
	</style>
</head>
<body>
	<div class="container cart-page">
		<div class="row">
			<div class="col-lg-8 mb-4">
				<div class="cart-card">
					<div class="cart-head d-flex align-items-center justify-content-between">
						<div>Shopping Cart</div>
						<a href="<?= htmlspecialchars($BASE)?>/index.php" class="btn btn-sm btn-outline-secondary">Continue Shopping</a>
					</div>

					<?php if(empty($items)): ?>
						<div class="p-4">
							<div class="card empty-card text-center p-5">
								<h5 class="mb-2">Your Cart is Empty</h5>
								<p class="text-muted">Add some products to see them here</p>
								<a href="<?= htmlspecialchars($BASE) ?>/index.php" class="btn btn-primary">Browse Products</a>
							</div>
						</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="table mb-0">
								<thead class="thead-light">
									<tr>
										<th style="width: 88px;">Image</th>
										<th>Products</th>
										<th class="text-right" style="width: 130px;">Unit Price</th>
										<th style="width: 140px;">Qty</th>
										<th class="text-right" style="width: 140px;">Total</th>
										<th class="text-center" style="width: 80px;">Remove</th>
									</tr>
								</thead>
								<tbody id="cartBody">
									<?php foreach($items as $it): 
										$img = (!empty($it['product_image']) && file_exists(__DIR__.'/admin/uploads/'.$it['product_image'])) ? 'admin/uploads/'.$it['product_image'] : 'assets/images/placeholder.png';
										$line = (float)$it['unit_price'] * (int)$it['qty'];
									?>
									<tr>
										<td><img src="<?= htmlspecialchars($img) ?>" class="cart-img" alt=""></td>
										<td><?= htmlspecialchars($it['product_name']) ?></td>
										<td class="text-right"><?= number_format((float)$it['unit_price'],2) ?></td>
										<td><?= (int)$it['qty'] ?></td>
										<td class="text-right"><?= number_format($line,2) ?></td>
										<td class="text-center"><button class="btn btn-sm btn-danger remove">&times;</button></td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

