<?php
include 'connection.php';
$id = $_GET['id'];
$query = "DELETE FROM books WHERE id = ?";
$params = array($id);
sqlsrv_query($conn, $query, $params);
header('Location: index.php');
?>