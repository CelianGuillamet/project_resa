<?php

include 'header.php';
$idOrder = $_GET['id_Order'];
$orders = getOrderDetails($pdo, $idOrder);


if ($orders[0]['Capacity'] < $orders[0]['nbPlaces']) {
    echo '<div class="alert alert-danger" role="alert">';
    echo 'Il n\'y a plus assez de places disponibles pour cette catégorie';
    echo '</div>';
    echo '<a href="cart.php" class="btn btn-primary">Retour au panier</a>';
    die;
} else {
    $sql = <<<SQL
UPDATE Orders
SET Status = 'Validée'
WHERE id_Order = :id_Order
SQL;

    $query = $pdo->prepare($sql);
    $query->bindValue(':id_Order', $idOrder);
    $query->execute();

    $sql = <<<SQL
UPDATE Placement_Categories_venue
SET capacity = capacity - :nbPlaces
WHERE id_Placement_Categories_venue = :id_Placement_Categories_venue
SQL;

    $query = $pdo->prepare($sql);
    $query->bindValue(':nbPlaces', $orders[0]['nbPlaces']);
    $query->bindValue(':id_Placement_Categories_venue', $orders[0]['id_Placement_Categories_venue']);
    $query->execute();
    header('Location: cart.php');
}
