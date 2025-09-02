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
	$sql = "SELECT ci.id AS item_id, ci.product_id, ci.qty, ci.unit_price, 
               p.product_name, p.product_image 
        FROM cart_items ci 
        JOIN products p ON p.id = ci.product_id 
        WHERE ci.cart_id = ?";
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
	<?php if (!defined('BOOTSTRAP_LOADED')): ?>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<?php endif; ?>
	<title>Products Cart</title>
	<style>
		.cart-page {
			padding: 24px 0;
		}

		.cart-card {
			border: 1px solid #e9ecef;
			border-radius: .5rem;
			overflow: hidden;
		}

		.cart-head {
			background: #f8f9fa;
			padding: .75rem 1rem;
			font-weight: 600;
		}

		.cart-img {
			width: 64px;
			height: 64px;
			object-fit: cover;
			border-radius: .25rem;
		}

		.qty {
			width: 96px;
		}

		.summary-card {
			position: sticky;
			top: 16px;
		}

		.empty-card {
			border: 1px dashed #ce4dea;
		}

		.table td,
		.table th {
			vertical-align: middle;
		}

		.remove {
			width: 36px;
			height: 36px;
			line-height: 1;
		}
	</style>
</head>

<body>
	<div class="container cart-page">
		<div class="row">
			<div class="col-lg-8 mb-4">
				<div class="cart-card">
					<div class="cart-head cart-head d-flex align-items-center justify-content-between">

						<div>Shopping Cart</div>
						<a href="<?= htmlspecialchars($BASE) ?>/index.php" class="btn btn-sm btn-outline-secondary">Continue Shopping</a>
					</div>

					<?php
					if (empty($items)): ?>
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
									<?php foreach ($items as $it):
										$img = (!empty($it['product_image']) && file_exists(__DIR__ . '/admin/uploads/' . $it['product_image'])) ? 'admin/uploads/' . $it['product_image'] : 'assets/images/placeholder.png';
										$line = (float)$it['unit_price'] * (int)$it['qty'];
									?>
										<tr data-item-id="<?= (int)$it['item_id'] ?>">
											<td>
												<img src="<?= htmlspecialchars($img) ?>" class="cart-img">
											</td>
											<td>
												<div class="font-weight-500">
													<?= htmlspecialchars($it['product_name']) ?>
												</div>
											</td>

											<td class="text-right">
												$<span class="unit"><?= number_format($it['unit_price'], 2) ?></span>
											</td>
											<td>
												<input type="number" name="qty" class="form-control form-control-sm qty" min="1" value="<?= (int)$it['qty'] ?>">
											</td>

											<td class="text-right">
												$<span class="line-total"><?= number_format($line, 2) ?></span>
											</td>
											<td class="text-center">
												<button class="btn btn-sm btn-outline-danger remove" title="Remove">&times;</button>
											</td>

										</tr>
									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<th colspan="4" class="text-right">Grand Total</th>
										<th class="text-right">$<span id="grandTotal"><?= number_format($grand, 2) ?></span></th>
									</tr>
								</tfoot>
							</table>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="card summary-card">
					<div class="card-header bg-white">
						<strong>Order Summary</strong>
					</div>
					<div class="card-body">
						<div class="d-flex justify-content-between mb-2">
							<span>Subtotal</span>
							<strong>$<span id="sumSubtotal"><?= number_format($grand, 2) ?></span></strong>
						</div>
						<div class="d-flex justify-content-between mb-2 text-muted">
							<span>Shipping</span>
							<span>Calculated at Checkout</span>
						</div>
						<hr>
						<div class="d-flex justify-content-between h5">
							<span>Total</span>
							<strong>$<span id="sumGrand"><?= number_format($grand, 2) ?></span></strong>
						</div>
					</div>
					<div class="card-footer bg-white">
						<?php if (!empty($items)): ?>
							<form method="post" action="cart_order.php" class="mb-2">
								<button class="btn btn-success btn-block">Order Now</button>
							</form>
						<?php endif; ?>
						<a href="<?= htmlspecialchars($BASE) ?>/index.php" class="btn btn-outline-secodary btn-block">Continue Shopping
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>

	<?php
	if (file_exists(__DIR__ . '/partials/footer.php')) include __DIR__ . '/partials/footer.php';
	?>

	<?php if (!defined('BOOTSTRAP_JS_LOADED')): ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
	<?php endif; ?>

	<script type="text/javascript">
		function recalcTotals() {
			let sum = 0;
			totalQty = 0;

			document.querySelectorAll('#cartBody tr').forEach(function(tr) {

				const unit = parseFloat((tr.querySelector('.unit')?.textContent || '0').replace(/,/g, ''));

				let qty = parseInt(tr.querySelector('.qty')?.value || '0', 10) || 0;

				const line = unit * qty;

				if (!isNaN(line)) sum += line;
				totalQty += qty;
			});

			const grand = sum.toFixed(2);
			const g1 = document.getElementById('grandTotal');
			const g2 = document.getElementById('sumSubtotal');
			const g3 = document.getElementById('sumGrand');

			if (g1) g1.textContent = grand;
			if (g2) g2.textContent = grand;
			if (g3) g3.textContent = grand;

			if (typeof window.updateNavCartBadge === 'function') {
				window.updateNavCartBadge(totalQty);
			} else {
				var badge = document.getElementById('navCartCount');
				if (badge) badge.textContent = totalQty;
			}

		}

		//Qty change + AJAX Update + UI update recalc

		document.addEventListener('input', function(e) {
			const qtyInput = e.target.closest('#cartBody .qty');
			if (!qtyInput) return;

			const tr = qtyInput.closest('tr');
			const itemId = tr?.getAttribute('data-item-id');
			let qty = parseInt(qtyInput.value, 10);
			if (!qty || qty < 1) qty = 1;
			qtyInput.value = qty;

			qtyInput.disabled = true;

			fetch('ajax/cart_update_qty.php', {

				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www.form-urlencode'
				},
				credentials: 'same-origin',
				body: 'item_id=' + encodeURIComponent(itemId) + '&qty=' + encodeURIComponent(qty)
			}).then(r => r.json()).then(d => {
				const unit = parseFloat((tr.querySelector('.unit')?.textContent || '0').replace(/,/g, ''));
				tr.querySelector('.line-total').textContent = (unit * qty).toFixed(2);
				recalcTotals();
			}).catch(() => {}).finally(() => {
				qtyInput.disabled = false;
			});
		});

		//Remove item-->AJAX + UI remove + totals

		document.addEventListener('click', function(e) {

			const btn = e.target.closest('#cartBody .remove');

			if (!btn) return;

			const tr = btn.closest('tr');
			const itemId = tr?.getAttribute('data-item-id');

			fetch('ajax/cart_remove_item.php', {

				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www.form-urlencode'
				},
				credentials: 'same-origin',
				body: 'item_id=' + encodeURIComponent(itemId)

			}).then(r => r.json()).then(d => {

				tr.parentNode.removeChild(tr);
				recalcTotals();

				if (!document.querySelectorAll('#cartBody tr')) {
					location.reload();
				}
			}).catch(() => {});
		});
	</script>
</body>

</html>