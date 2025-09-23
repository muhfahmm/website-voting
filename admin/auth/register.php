<?php
require '../../db/db.php';
if (isset($_POST['register'])) {
    // ambil parameter name
    $username = htmlspecialchars(strtolower($_POST['username']));
    $password = htmlspecialchars(strtolower($_POST['password']));
    $password2 = htmlspecialchars(strtolower($_POST['password2']));

    // cek apakah password 1 dan 2 sudah sama
    if ($password !== $password2) {
        echo "password tidak sama";
    } else {
        // cek username apakah sudah dipakai (dengan mysqli_query SELECT * FROM)
        $check = mysqli_query($db, "SELECT * FROM tb_admin WHERE username = '$username' ");
        // hash password
        $pass_hash = password_hash($password, PASSWORD_BCRYPT);
        // simpan ke database (dengan mysqli_query INSERT INTO)
        mysqli_query($db, "INSERT INTO tb_admin
                        (username,password)
                        VALUES
                        ('$username', '$password_hash')
                        ");
        header("Location: login.php");
        exit;
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
            </form>
        </div>
    </div>
</body>

</html>