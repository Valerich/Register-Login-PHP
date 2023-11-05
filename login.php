<?php
session_start();
if (isset($_SESSION["user"])) {
    header("location: profile.php");
}
error_reporting(E_ERROR);
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма авторизации</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
</head>

<body>
    <div class="index-link">
        <a href="index.php">На главную</a>
    </div>
    <div class="container">
        <?php
        //Обрабодка авторизации:
        if (isset($_POST["submitLog"])) {
            $login = ($_POST["login"]);
            $password = ($_POST["password"]);
            require_once "database.php";
        
            //Yandex капча:
            define('SMARTCAPTCHA_SERVER_KEY', 'ysc2_27ubNBOy8cbr4yQne04xrCFSJVKOrhIeeGkxfqlrb2585896');
            function check_captcha($token)
            {
                $ch = curl_init();
                $args = http_build_query([
                    "secret" => SMARTCAPTCHA_SERVER_KEY,
                    "token" => $token,
                    "ip" => $_SERVER['REMOTE_ADDR'],
                ]);
                curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        
                $server_output = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
        
                if ($httpcode !== 200) {
                    echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
                    return true;
                }
                $resp = json_decode($server_output);
                return $resp->status === "ok";
            }
        
            $token = $_POST['smart-token'];
            if (check_captcha($token)) {
                //Проверка логина по почте
                $sql = "SELECT * FROM `users` WHERE email = '$login'";
                $result = mysqli_query($conn, $sql);
                $userMail = mysqli_fetch_array($result, MYSQLI_ASSOC);
                //Проверка логина по номеру
                $sql = "SELECT * FROM `users` WHERE tel_number = '$login'";
                $result = mysqli_query($conn, $sql);
                $userTel = mysqli_fetch_array($result, MYSQLI_ASSOC);
                //Проверка пароля
                if ($userMail || $userTel) {
                    if (password_verify($password, $userMail["password"])) {
                        session_start();
                        $_SESSION["user"] = $userMail;
                        header("location: profile.php");
                        die();
                    } elseif (password_verify($password, $userTel["password"])) {
                        session_start();
                        $_SESSION["user"] = $userTel;
                        header("location: profile.php");
                        die();
                    } else {
                        echo "<div class='alert alert-danger'>Неверный пароль</div>";;
                    }
                } else {
                    echo "<div class='alert alert-danger'>Неверный логин</div>";;
                }
            } else {
                echo "<div class='alert alert-danger'>Ошибка: Подтвердите что вы не робот</div>";
            }
        }
        ?>
        <form action="login.php" method="post">
            <h3>Авторизация</h3>
            <p>Введите почту или номер:</p>
            <div class="form-group">
                <input type="text" class="form-control" name="login" placeholder="Логин">
            </div>
            <p>Введите свой пароль:</p>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Пароль">
            </div>
            <div class="link">
                <a href="register.php">Зарегистрироваться</a>
            </div>
            <div 
            id="captcha-container"
            class="smart-captcha"
            data-sitekey="ysc1_27ubNBOy8cbr4yQne04xEAkVwXB6E5XB6bpZvGUB0805d080"
            ></div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Войти" name="submitLog">
            </div>
        </form>
    </div>
</body>

</html>