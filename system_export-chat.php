<?php
require_once 'db.php';

//Данные
$id = $_POST['id']; //Идентификатор вашего виджета
$siteApiKey = $_POST['siteApiKey']; //Секретный ключ виджета
$accountApiKey = $_POST['accountApiKey']; //Секретный ключ виджета
$chan = $_POST['chan']; //Идентификатор страницы (channel)
$action = $_POST['action']; //Действие

if ($action == 'export') {
    createTable($chan);
    addInDB($id, $siteApiKey, $accountApiKey, $chan, 0);
}

//ответ от API
function sendFromCURL($id, $siteApiKey, $accountApiKey, $chan, $commentId)
{
    $re = shell_exec("curl --location --request GET 'http://cackle.me/api/3.0/comment/list.json?'"
        . "'id='" . $id
        . "'&siteApiKey='" . $siteApiKey
        . "'&accountApiKey='" . $accountApiKey
        . "'&chan='" . $chan
        . "'&commentId='" . $commentId);
    return json_decode($re, true);
}

//Создание таблицы в БД
function createTable($table)
{
    global $mysqli;
    $null_table = "CREATE TABLE `$table` (
		`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`comment_id` INT(255) default NULL,
        `site_id` INT(255) default NULL,
        `comment_message` VARCHAR(255) default NULL,
        `comment_status` VARCHAR(255) default NULL,
        `user_id` VARCHAR(255) default NULL,
        `user_name` VARCHAR(255) default NULL,
        `user_ip` VARCHAR(255) default NULL,
		`created` TIMESTAMP default CURRENT_TIMESTAMP()
		) ENGINE = MYISAM CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
    if (!$mysqli->query($null_table)) die("Не удалось создать таблицу: (" . $mysqli->errno . ") " . $mysqli->error);
}

//Добавление данных в БД
function addInDB($id, $siteApiKey, $accountApiKey, $chan, $commentId)
{
    global $mysqli;
    $comments = sendFromCURL($id, $siteApiKey, $accountApiKey, $chan, $commentId);
    foreach ($comments as $comment) {
        if (count($comment) < 100) {
            for ($i = 0; $i < count($comment); $i++) {
                $comment_id = $comment[$i]['id'];
                $site_id = $comment[$i]['siteId'];
                $comment_message = $comment[$i]['message'];
                $comment_status = $comment[$i]['status'];
                $user_id = $comment[$i]['author']['id'];
                $user_name = $comment[$i]['author']['name'];
                $user_ip = $comment[$i]['ip'];
                $mysqli->query("INSERT INTO `$chan` (`comment_id`, `site_id`, `comment_message`, `comment_status`, `user_id`, `user_name`, `user_ip`)
                                VALUES ('$comment_id', '$site_id', '$comment_message', '$comment_status', '$user_id', '$user_name', '$user_ip')");
            }
        } elseif (count($comment) == 100) {
            for ($i = 0; $i < count($comment); $i++) {
                $comment_id = $comment[$i]['id'];
                $site_id = $comment[$i]['siteId'];
                $comment_message = $comment[$i]['message'];
                $comment_status = $comment[$i]['status'];
                $user_id = $comment[$i]['author']['id'];
                $user_name = $comment[$i]['author']['name'];
                $user_ip = $comment[$i]['ip'];
                $mysqli->query("INSERT INTO `$chan` (`comment_id`, `site_id`, `comment_message`, `comment_status`, `user_id`, `user_name`, `user_ip`)
                                VALUES ('$comment_id', '$site_id', '$comment_message', '$comment_status', '$user_id', '$user_name', '$user_ip')");
            }
            addInDB($id, $siteApiKey, $accountApiKey, $chan, $comment[99]['id']);
        }
    }
}