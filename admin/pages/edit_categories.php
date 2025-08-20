<?php 
include 'dbConfig.php';

$errmsg = '';
$successmsg = '';
$categories = ['id' => '', 'categories_name' => ''];

if (isset($_GET['id'])) {
    $decoded_id = base64_decode(urldecode($_GET['id']));
    $stmt = $DB_con->prepare("SELECT * FROM categories WHERE id=?");
    $stmt->execute([$decoded_id]);
    $categories = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $errmsg = "Id not found";
}

if (isset($_POST['Edit'])) {
    $category_name = $_POST['category_name'];

    if (!empty($category_id) && !empty($category_name)) {
        $stmt = $DB_con->prepare("UPDATE categories SET category_name = ? WHERE id = ?");
        $updated = $stmt->execute([$category_name, $category_id]);

        if ($updated) {
            $successmsg = "Category '$category_name' updated successfully.";
            header("Refresh:3; URL=?page=categories");
        } else {
            $errmsg = "Failed to update category.";
        }
    } else {
        $errmsg = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Category</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5" style="max-width: 400px;">
  <?php if (!empty($successmsg)): ?>
    <div class="alert alert-success"><?php echo $successmsg;?></div>
  <?php elseif (!empty($errmsg)): ?>
    <div class="alert alert-danger"><?php echo $errmsg; ?></div>
  <?php endif; ?>

  <form action="#" method="POST" class="border p-4 rounded shadow-sm bg-light">
    <h5 class="mb-3 text-center">Edit Category</h5>


    <div class="mb-3">
        <label>Category Name</label>
        <input type="text" name="category_name" class="form-control" placeholder="Category name" required value="<?php echo $categories['category_name']; ?>">
    </div>

    <div class="d-grid">
        <button type="submit" name="Edit" class="btn btn-primary">Update Category</button>
    </div>
  </form>
</div>

</body>
</html>
