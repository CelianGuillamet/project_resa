<?php

include 'header.php';
$idUser = getIdUser($pdo, $_SESSION['username']);
$idOrder = $_GET['id_Order'];
$orders = getOrderDetails($pdo, $idOrder);


if (!empty($orders)) {
    displayOrderDetails($orders);
} else {
    echo "Order not found.";
}

include 'footer.php';
