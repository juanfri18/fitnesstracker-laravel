<?php
$conn = new mysqli('localhost', 'root', '', 'fitness_tracker');
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
$result = $conn->query("SHOW CREATE TABLE usuarios");
$row = $result->fetch_assoc();
echo "usuarios:\n" . $row['Create Table'] . "\n\n";

$result = $conn->query("SHOW CREATE TABLE entrenamientos");
$row = $result->fetch_assoc();
echo "entrenamientos:\n" . $row['Create Table'] . "\n\n";

$result = $conn->query("SHOW CREATE TABLE ejercicios");
$row = $result->fetch_assoc();
echo "ejercicios:\n" . $row['Create Table'] . "\n\n";
