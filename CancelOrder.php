<?php

include 'header.php';
$idOrder = $_GET['id_Order'];
$orders = getOrderDetails($pdo, $idOrder);

$sql = <<<SQL
UPDATE Orders
SET Status = 'Annulée'
WHERE id_Order = :id_Order
SQL;

$query = $pdo->prepare($sql);
$query->bindValue(':id_Order', $idOrder);
$query->execute();
