<?php
$pdo = new PDO("mysql:host=localhost;dbname=website_flower", "root", "");
$id = $_GET['id'];
$pdo->query("DELETE FROM products WHERE id = $id");
header('Location: index.php');
?>