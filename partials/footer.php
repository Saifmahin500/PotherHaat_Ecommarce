<footer class="bg-dark text-light pt-5 pb-4 mt-5">
	<div class="container">
		<div class="row">

			<!--Column-->
			<div class="col-md-4 col-sm-12 mb-4">
				<h5 class="text-uppercase mb-3">About Us</h5>
				<p>
					Welcome to our Ecommerce store. We provide the best quality products at affordable price. Your satisfaction is our top priority.
				</p>
			</div>

			<!--Column-->
			<div class="col-md-4 col-sm-12 mb-4">
				<h5 class="text-uppercase mb-3">Quick Links</h5>
				<ul class="list-unstyled">
					<li><a href="index.php" class="text-light">Home</a></li>
					<li><a href="all_products.php" class="text-light">All Products</a></li>
					<li><a href="about.php" class="text-light">About Us</a></li>
					<li><a href="contact.php" class="text-light">Contact Us</a></li>
					<li><a href="login.php" class="text-light">Login</a></li>
				</ul>
			</div>

			<!--Column 3-->
			<div class="col-md-4 col-sm-12 mb-4">
				<h5 class="text-uppercase mb-3">Contact Us</h5>
				<p><i class="fa fa-mapmarker mr-2"></i>29, Purana Paltan, Noorjahan Sharif Plaza</p>
				<p><i class="fa fa-phone mr-2"></i>+88-01856-590532</p>
				<p><i class="fa fa-envelope mr-2"></i>info@PotherHaat.com</p>

				<div>
					<a href="#" class="text-light mr-3"><i class="fab fa-facebook fa-lg"></i></a>
					<a href="#" class="text-light mr-3"><i class="fab fa-twitter fa-lg"></i></a>
					<a href="#" class="text-light mr-3"><i class="fab fa-instagram fa-lg"></i></a>
					<a href="#" class="text-light mr-3"><i class="fab fa-linkedin fa-lg"></i></a>
				</div>
			</div>
		</div>

		<hr class="bg-light">
		<div class="text-center">
			<p class="mb-0">&copy; <?php echo date("Y"); ?>SaifMahin. All Rights Reserved.</p>
		</div>
	</div>
</footer>


<!-- jquery cdn -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- bootstrap cdn -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
	function loadProducts(catIds = []) {
		$.ajax({
			url: 'fetch_products.php',
			method: 'POST',
			data: {
				categories: catIds
			},
			success: function(html) {
				$('#productGrid').html(html);
			}
		});
	}

	$(function() {
		const checked = [];

		$('.cat-check:checked').each(function() {
			checked.push($(this).val());
		});

		loadProducts(checked);

		$(document).on('change', '.cat-check', function() {

			const ids = [];
			$('.cat-check:checked').each(function() {
				ids.push($(this).val());
			});

			loadProducts(ids);
		});

		$('#clearFilter').on('click', function() {

			$('.cat-check').prop('checked', false);

			loadProducts([]);
		});

		const qp = new URLSearchParams(window.location.search);
		if (qp.get('view') == 'all') {
			loadProducts([]);
		}
	});
</script>