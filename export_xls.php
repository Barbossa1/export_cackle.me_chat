<?php
require_once 'vendor/autoload.php';
require_once 'db.php';

//Экспорт данных в виде таблицы
$table = $_GET['table'];
$result = $mysqli->query("SELECT * FROM `$table`");
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}
array_unshift($items, array_keys($items[0]));
Shuchkin\SimpleXLSXGen::fromArray($items)
    ->setDefaultFont('Arial')
    ->setDefaultFontSize(12)
    ->downloadAs($table . '.xlsx');

