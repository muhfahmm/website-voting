<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <form action="" method="post">
                <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
                <div class="form-box">
                    <input type="text" name="username" placeholder="username" required>
                </div>
                <div class="form-box">
                    <input type="password" name="password" placeholder="password" required>
                </div>
                <div class="form-box">
                    <button name="login" type="submit">login</button>
                </div>
            </form>
            <?php
            require '../../db/db.php';
            if (isset($_POST['login'])) {
                // ambil parameter name
                $username = htmlspecialchars(strtolower($_POST['username']));
                $password = htmlspecialchars(strtolower($_POST['password']));

                // ambil parameter username
                $result = mysqli_query($db, "SELECT * FROM tb_admin WHERE username = '$username'");
                // cek apakah ada usernamenya terdaftar di database
                if (mysqli_num_rows($result) === 1) {
                    $user = mysqli_fetch_assoc($result);

                    // cek password
                    if (password_verify($password, $user['password'])) {
                        // login berhasil, set session
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['user_id'] = $user['id'];
                        header("Location: index.php");
                        exit;
                    }
                    // jika password salah
                    else {
                        $error = "password salah";
                    }
                } 
                // jika username tidak ditemukan
                else {
                    $error = "username tidak ditemukan";
                }
            }
            ?>
        </div>
    </div>
</body>

</html> -->