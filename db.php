<?php
//Подключение к БД
$mysqli = new mysqli("localhost", "root", "", "export_chat");
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno) die("Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);