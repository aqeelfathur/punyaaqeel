<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == "admin" && $password == "1234") {

        header("Location: data.html");
        exit();
    } else {

        echo "Username atau password salah! <a href='login.html'>Coba lagi</a>";
    }
}
?>
