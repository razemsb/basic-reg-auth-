<?php
session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $pass = $_POST['pass'];

    $conn = new mysqli($host, $user, $password, $db);

    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT Password FROM users WHERE Login = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->bind_result($hashed_pass);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($pass, $hashed_pass)) {

        $_SESSION['user_login'] = $name;

        echo "<script>alert('Успешная авторизация'); window.location.href = 'page.php';</script>";
    } else {
        echo "<script>alert('Неверный логин или пароль'); window.location.href = 'index.php';</script>";
    }

    $conn->close();
}
?>
