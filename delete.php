<?php
include 'database.php';

$id = $_GET['id'] ?? 0;
$conn->query("DELETE FROM animal WHERE id = $id");
header("Location: index.php");
exit;