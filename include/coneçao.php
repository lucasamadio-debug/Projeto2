<?php
$servername = "192.168.56.101"; 
$username = "lucas";
$password = "luna2803";              
$dbname = "thiago lanches";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?> 