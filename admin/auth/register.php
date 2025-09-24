<?php
require '../../db/db.php';
if (isset($_POST['register'])) {
    // ambil parameter name
    $username = htmlspecialchars(strtolower($_POST['username']));
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $password2 = mysqli_real_escape_string($db, $_POST['password2']);

    // cek apakah password 1 dan 2 sama
    if ($password !== $password2) {

    } else {
        // cek username sudah ada atau belum
        $check = mysqli_query($db, "SELECT * FROM tb_admin WHERE username = '$username' ");
        // jika username sudah dipakai
        if (mysqli_num_rows($check) > 0) {
            echo "username sudah dipakai";
        } else {
            // hash password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // simpan ke database
            mysqli_query($db, "INSERT INTO tb_admin 
            (username,password) VALUES
            ('$username', ' $hash')");
            header("Location: login.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <form action="" method="post">
                <div class="form-box">
                    <input type="text" name="username" placeholder="username" required>
                </div>
                <div class="form-box">
                    <input type="password" name="password" placeholder="password" required>
                </div>
                <div class="form-box">
                    <input type="password" name="password2" placeholder=" konfirmasi password" required>
                </div>
                <div class="form-box">
                    <button name="register" type="submit">register</button>
                </div>
                <a href="login.php">Login</a>
            </form>
        </div>
    </div>
</body>

</html>