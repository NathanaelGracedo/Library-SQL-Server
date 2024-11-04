<?php
// File ini akan menghubungkan aplikasi dengan database SQL Server
$serverName = "NATHANAEL";
$connectionOptions = array(
    "Database" => "Library",
    "Uid" => "",  
    "PWD" => ""   
);

// Membuat koneksi
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
}
?>