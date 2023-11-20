<?php 
include 'header.php';
$idUser = getIdUser($pdo, $_SESSION['username']);

$sql = <<<SQL

SELECT *
FROM Orders
WHERE id_User = :id_User AND Status = 'En cours'
SQL;

$query = $pdo->prepare($sql);
$query->bindValue(':id_User', $idUser['id_User']);
$query->execute();
$orders = $query->fetchAll(PDO::FETCH_ASSOC);



foreach ($orders as $order) {
    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<div class="card">';
    echo '<div class="card-header">';
    echo '<h3>Commande n°' . $order['OrderNumber'] . '</h3>';
    echo '</div>';
    echo '<div class="card-body">';
    echo '<div class="row">';
    echo '<div class="col-md-6">';
    echo '<h4 class="card-title">Date de la commande : ' . $order['OrderDate'] . '</h4>';
    echo '<p class="card-text">Statut de la commande : ' . $order['Status'] . '</p>';
    echo '<p class="card-text">Montant total : ' . $order['TotalCost'] . '€</p>';
    echo '<p class="card-text">Numéro de commande : ' . $order['OrderNumber'] . '</p>';
    echo '</div>';
    echo '<div class="text-right col-md-6">';
    echo '<a href="orderDetails.php?id_Order=' . $order['id_order'] . '" class="btn btn-primary">Détails</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

}