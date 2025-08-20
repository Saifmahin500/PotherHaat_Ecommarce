<?php
require_once __DIR__ . '/partials/header.php';
require_once __DIR__ . '/partials/navbar.php';
require_once __DIR__ . '/config/dbconfig.php';

$flag = $_GET['msg'] ?? '';

?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


<div class="container mt-4">
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4">
                    <h3 class="mb-4  fw-bold" style="color: #B448CF;">
                        <i class="fa-solid fa-envelope me-2"></i> Contact Us
                    </h3>

                    <?php if ($flag === 'ok'):  ?>
                        <div class="alert alert-success">
                            ✅ Thanks! Your message has been submitted.
                        </div>
                    <?php elseif ($flag === 'err'):  ?>
                        <div class="alert alert-danger">
                            ❌ Something went wrong, please try again.
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="save_contact.php" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control" required>
                            <div class="invalid-feedback">Name is required.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                            <div class="invalid-feedback">Valid email is required.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                            <div class="invalid-feedback">Enter your subject.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" rows="5" class="form-control" required></textarea>
                            <div class="invalid-feedback">Message cannot be empty.</div>
                        </div>

                        <button class="btn fw-bold px-4 py-2" style="background-color: #B448CF; color:rgb(245, 245, 245);">
                            <i class="bi bi-send-fill me-1"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mt-3 mt-lg-0">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-3 text-secondary fw-bold" >
                        <i class="bi bi-geo-alt-fill me-2"></i> Our Office
                    </h4>
                    <p class="mb-2"><i class="fa-solid fa-location-dot me-2" style="color: #B448CF;"></i>29 Purana Paltan, Noorjahan Sharif Plaza</p>
                    <p class="mb-2"><i class="fa-solid fa-phone me-2" style="color: #B448CF;"></i><strong>Phone:</strong> +88 01856-590532</p>
                    <p class="mb-0"><i class="fa-solid fa-envelope me-2" style="color: #B448CF;"></i><strong>Email:</strong> info@PotherHaat.com</p>
                </div>
            </div>

            <!-- Google Map Embed -->
            <div class="card shadow-sm mt-3">
                <div class="card-body p-0">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d112256.41077283815!2d90.29221158983356!3d23.7967209321402!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b96ad177ac5b%3A0x7ea0275cf8228b81!2sNur%20jahan%20sharif%20plaza!5e1!3m2!1sen!2sbd!4v1755585807624!5m2!1sen!2sbd"
                        width="100%"
                        height="250"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    (function() {
        'use strict';
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>


<?php require_once __DIR__ . '/partials/footer.php'; ?>