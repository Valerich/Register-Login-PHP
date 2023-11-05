<?php
$hostName = "localhost";
$bdUser = "root";
$bdPassword = "";
$bdName = "register_formbd";

$conn = mysqli_connect($hostName, $bdUser, $bdPassword, $bdName);
if (!$conn) {
    die("Ошибка подключения");
}
