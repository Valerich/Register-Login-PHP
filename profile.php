<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница пользователя</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="index-link">
        <a href="index.php">На главную</a>
    </div>
    <div class="container-page">
        <h1>Добро пожаловать</h1>
        <div class="index-link">
            <a href="profile_edit.php" class="btn btn-warning">Редактировать профиль</a>
            <a href="logout.php" class="btn btn-dark">Выйти из аккаунта</a>
        </div>
    </div>
</body>

</html>