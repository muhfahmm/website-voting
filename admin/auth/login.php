<?php
require '../../db/db.php';
if (isset($_POST['login'])) {
    // ambil parameter name
    $username = htmlspecialchars(strtolower($_POST['username']));
    $password = htmlspecialchars(strtolower($_POST['password']));

}
?>
<!DOCTYPE html>
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
        </div>
    </div>
</body>

</html>