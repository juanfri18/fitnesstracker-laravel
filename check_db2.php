<?php
$conn = new mysqli('localhost', 'root', '', 'fitness_tracker');
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
$out = "";
$result = $conn->query("SHOW CREATE TABLE usuarios");
if($row = $result->fetch_assoc()) $out .= "usuarios:\n" . $row['Create Table'] . "\n\n";

$result = $conn->query("SHOW CREATE TABLE entrenamientos");
if($row = $result->fetch_assoc()) $out .= "entrenamientos:\n" . $row['Create Table'] . "\n\n";

$result = $conn->query("SHOW CREATE TABLE ejercicios");
if($row = $result->fetch_assoc()) $out .= "ejercicios:\n" . $row['Create Table'] . "\n\n";

file_put_contents('db_schema_debug.txt', $out);
