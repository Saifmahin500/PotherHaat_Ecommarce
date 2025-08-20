<?php
session_start();

include("dbConfig.php");

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    $stmt = $DB_con->prepare('SELECT * FROM admins WHERE username = ?');
    $stmt->execute([ $username]);

    if($stmt->rowCount() === 1){
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($password,$admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];

            header("location: index.php");
            exit();
        }
        else{
            $error = "Invalid Password";
        }

    }

    else
    {
        $error = "Admin user not found";
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- <style>
        .login-form {
            max-width: 400px;
            margin: 0 auto;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .login-form button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .login-form button:hover {
            background-color: #0056b3;
        }

        .login-form .form-group {
            margin-bottom: 20px;
        }
    </style> -->
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title text-center">Admin Login</h4>

                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>