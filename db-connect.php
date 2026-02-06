<?php
/**
 * ZabencoCorp Portal Database Connection
 */

$host = 'zabencocorp-db-01.cfioyem0wxq4.us-east-2.rds.amazonaws.com';
$dbname = 'zabencocorp_portal';
$user = 'zabencorp';
$password = 'zFle3ux!1';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
