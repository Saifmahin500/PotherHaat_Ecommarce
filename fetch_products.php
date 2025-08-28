<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/admin/dbConfig.php';

$catIds = isset($_POST['categories']) ? $_POST['categories'] : [];
$catIds = is_array($catIds) ? array_filter($catIds) : [];

if ($catIds) {
	$in = implode(',', array_fill(0, count($catIds), '?'));
	$sql = "SELECT p.*, c.category_name, a.sizes, a.colors FROM products p LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN attributes a ON a.product_id = p.id WHERE p.category_id IN ($in) ORDER BY p.product_name ASC";

	$stmt = $DB_con->prepare($sql);
	$stmt->execute($catIds);
} else {
	$sql = "SELECT p.*, c.category_name, a.sizes, a.colors FROM products p LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN attributes a ON a.product_id = p.id ORDER BY p.product_name ASC";
	$stmt = $DB_con->query($sql);
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

function productThumb($row)
{
	$fsBase = __DIR__ . "/admin/uploads/";
	$urlBase = "admin/uploads/";

	if (!empty($row['product_image']) && file_exists($fsBase . $row['product_image'])) {
		return $urlBase . htmlspecialchars($row['product_image']);
	}

	if ($row['book_type'] == 'downloadable' && !empty($row['virtual_file'])) {
		$pdfIcon = 'pdf-icon.png';
		if (file_exists($fsBase . $pdfIcon)) {
			return $urlBase . $pdfIcon;
		}
	}

	return "assets/images/myImage.png";
}

ob_start();

if (!$products) {
	echo '<div class="col-12"><div class="alert alert-info">No Products Found!</div></div>';
} else {
	foreach ($products as $p) {
		$img = productThumb($p);

?>

		<div class="col-sm-6 col-md-4 col-lg-3 mb-4">
			<div class="product-card shadow-sm rounded h-100 ">
				<div class="product-img">
					<img src="<?= $img ?>" alt="<?= $p['product_name'] ?>" class="img-fluid">
				</div>
				<div class="p-body p-3 " style="display:flex; flex-direction:column; height:100%;">
					<h6 class="mb-1 text-dark fw-bold"><?= $p['product_name'] ?></h6>
					<div class="text-muted small mb-2">
						<?= $p['category_name'] ?? '--' ?>
					</div>

					<div class="mb-4">
						<span class="fw-bold text-success"><?= (int)$p['selling_price'] ?> TK</span>
					</div>
					<div class="align-items-center">
						<?php if (!empty($_SESSION['user_id'])):   ?>
							<form method="$_POST" action="cart_add.php" class="m-0">
								<input type="hidden" name="qty" value="1">
								<button type="submit" style="border:1px solid #B448CF; color:#B448CF; background-color:transparent; transition:0.3s; padding:10px; font-size:14px; text-decoration:none; display:block; width:100%; text-align:center;"
									onmouseover="this.style.backgroundColor='#B448CF'; this.style.color='#fff';"
									onmouseout="this.style.backgroundColor='transparent'; this.style.color='#B448CF';">
									<i class="fas fa-shopping-cart me-1"></i>Add to Cart

								</button>
							</form>
						<?php else: ?>
							<button type="button"
								style="border:1px solid #B448CF; color:#B448CF; background-color:transparent; transition:0.3s; padding:10px; font-size:14px; text-decoration:none; display:block; width:100%; text-align:center;"
								onmouseover="this.style.backgroundColor='#B448CF'; this.style.color='#fff';"
								onmouseout="this.style.backgroundColor='transparent'; this.style.color='#B448CF';"
								data-bs-toggle="modal" data-bs-target="#loginModal">
								<i class="fas fa-shopping-cart me-1"></i>Login to Add Cart
							</button>
						<?php endif; ?>
						<!--Login Modal-->
						<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<form class="modal-content" method="post" action="<?= $BASE ?>/auth/login.php">
									<div class="modal-header">
										<h5 class="modal-title">Login to your account</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<div class="form-group mb-3">
											<label>Email:</label>
											<input type="email" name="email" class="form-control" required autocomplete="email">
										</div>
										<div class="form-group mb-3">
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

					</div>
				</div>
			</div>
		</div>

<?php
	}
}

echo ob_get_clean();

?>