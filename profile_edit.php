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
    <title>Редактирование профиля</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="index-link">
        <a href="index.php">На главную</a>
    </div>
    <div class="container">
        <?php
        //Обрабодка новых данных:
        if (isset($_POST["submit"])) {
            $newName = trim($_POST["user_name"]);
            $newEmail = trim($_POST["email"]);
            $newNumber = trim($_POST["tel_number"]);
            $newPassword = trim($_POST["password"]);
            if (!empty($newPassword)) {
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT); //Хэш введенного пароля
            }
            
            require_once "database.php"; //Подключение базы данных
            $errors = array();
            if (!empty($newEmail)) {
                if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Некорректный формат почты");
                }
                //Проверка почты на уникальность
                $sql = "SELECT * FROM `users` WHERE email = '$newEmail'";
                $result = mysqli_query($conn, $sql);
                $rowCount = mysqli_num_rows($result);
                if ($rowCount > 0) {
                    array_push($errors, "Почта уже зарегистрирована");
                }
            }
            //Проверка номера на уникальность
            if (!empty($newNumber)) {
                $sql = "SELECT * FROM `users` WHERE tel_number = '$newNumber'";
                $result = mysqli_query($conn, $sql);
                $rowCount = mysqli_num_rows($result);
                if ($rowCount > 0) {
                    array_push($errors, "Номер уже зарегистрирован");
                }
            }
            //Проверка имени на уникальность
            if (!empty($newName)) {
                $sql = "SELECT * FROM `users` WHERE user_name = '$newName'";
                $result = mysqli_query($conn, $sql);
                $rowCount = mysqli_num_rows($result);
                if ($rowCount > 0) {
                    array_push($errors, "Пользователь с таким именем уже зарегистрирован");
                }
            }
            //Вывод ошибок 
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-warning'>$error</div></br>";
                }
            } else {
                //Сохранение новых данных в бд
                $userId = $_SESSION["user"]["id"];
                if (!empty($newName)) {
                    $sqlName = "UPDATE `users` SET user_name = '$newName' WHERE id = $userId";
                    $resultName = mysqli_query($conn, $sqlName);
                    if ($resultName == true) {
                        echo "<div class='alert alert-success'>Новое имя сохранено</div>";
                    } else {
                        echo "Что-то пошло не так:" . $conn->error;
                    }
                }
                if (!empty($newEmail)) {
                    $sqlMail = "UPDATE `users` SET email = '$newEmail' WHERE id = $userId";
                    $resultMail = mysqli_query($conn, $sqlMail);
                    if ($resultMail == true) {
                        echo "<div class='alert alert-success'>Новая почта сохранена</div>";
                    } else {
                        echo "Что-то пошло не так:" . $conn->error;
                    }
                }
                if (!empty($newNumber)) {
                    $sqlNum = "UPDATE `users` SET tel_number = '$newNumber' WHERE id = $userId";
                    $resultNum = mysqli_query($conn, $sqlNum);
                    if ($result == true) {
                        echo "<div class='alert alert-success'>Новый номер сохранён</div>";
                    } else {
                        echo "Что-то пошло не так:" . $conn->error;
                    }
                }
                if (!empty($newPassword)) {
                    $sqlPass = "UPDATE `users` SET password = '$passwordHash' WHERE id = $userId";
                    $resultPass = mysqli_query($conn, $sqlPass);
                    if ($resultPass == true) {
                        echo "<div class='alert alert-success'>Новый пароль сохранён</div>";
                    } else {
                        echo "Что-то пошло не так:" . $conn->error;
                    }
                }
            }
        }
        ?>
        <form action="profile_edit.php" method="post">
            <h3>Введите новые данные:</h3>
            <div class="form-group">
                <input type="text" class="form-control" name="user_name" placeholder="Имя:">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Почта:">
            </div>
            <div class="form-group">
                <input type="tel" class="form-control" name="tel_number" placeholder="Телефон:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Пароль:">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-success" value="Сохранить изменения" name="submit">
            </div>
            <div class="form-btn">
                <a href="profile.php" class="btn btn-warning">Вернуться в профиль</a>
            </div>
        </form>
    </div>
</body>

</html>