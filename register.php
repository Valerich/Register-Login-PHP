<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма регистрации</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="index-link">
        <a href="index.php">На главную</a>
    </div>
    <div class="container">
        <?php
        //Обрабодка входных данных:
        if (isset($_POST["submit"])) {
            $userName = trim($_POST["user_name"]);
            $email = trim($_POST["email"]);
            $telNumber = trim($_POST["tel_number"]);
            $password = trim($_POST["password"]);
            $repeatPassword = trim($_POST["repeat_password"]);

            $passwordHash = password_hash($password, PASSWORD_DEFAULT); //Хэш введенного пароля

            //Проверка на корректность:
            $errors = array();
            if (empty($userName) || empty($email) || empty($telNumber) || empty($password) || empty($repeatPassword)) {
                array_push($errors, "Заполнены не все поля");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Некорректный формат почты");
            }
            if ($password !== $repeatPassword) {
                array_push($errors, "Пароли не совпадают");
            }
            require_once "database.php"; //Подключение базы данных

            //Проверка почты на уникальность
            $sql = "SELECT * FROM `users` WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                array_push($errors, "Почта уже зарегистрирована");
            }
            //Проверка номера на уникальность
            $sql = "SELECT * FROM `users` WHERE tel_number = '$telNumber'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                array_push($errors, "Номер уже зарегистрирован");
            }
            //Проверка имени на уникальность
            $sql = "SELECT * FROM `users` WHERE user_name = '$userName'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                array_push($errors, "Пользователь с таким именем уже зарегистрирован");
            }
            //Вывод ошибок 
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                //Добавление данных в бд
                $sql = "INSERT INTO `users`(user_name, email, tel_number, password) VALUES ('$userName', '$email', '$telNumber', '$passwordHash')";
                $result = mysqli_query($conn, $sql);
                if ($result == true) {
                    header("location: success_reg.php");
                } else {
                    echo "Что-то пошло не так:" . mysqli_error($conn);
                }
            }
        }
        ?>
        <form action="register.php" method="post">
            <h3>Регистрация</h3>
            <div class="form-group">
                <input type="text" class="form-control" name="user_name" placeholder="Ваше имя:">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Почта:">
            </div>
            <div class="form-group">
                <input type="tel" class="form-control" name="tel_number" placeholder="Телефон:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Придумайте пароль:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="repeat_password" placeholder="Повторите пароль:">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Регистрация" name="submit">
            </div>
        </form>
        <div class="link">
            <a href="login.php">Войти</a>
        </div>
    </div>
</body>

</html>