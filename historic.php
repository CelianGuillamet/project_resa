<?php
include 'header.php';

$idUser = getIdUser($pdo, $_SESSION['username']);
$sql = <<<SQL
SELECT *
FROM Orders
WHERE id_User = :id_User AND Status != 'En cours'
SQL;

$query = $pdo->prepare($sql);
$query->bindValue(':id_User', $idUser['id_User']);
$query->execute();
$orders = $query->fetchAll(PDO::FETCH_ASSOC);

echo '<div class="container">';
echo '<div class="row">';
echo '<div class="col-md-12">';
echo '<div class="card">';
echo '<div class="card-header">';
echo '<h3>Historique des commandes</h3>';
echo '</div>';
echo '<div class="card-body">';
echo '<div class="row">';
echo '<div class="col-md-12">';
echo '<table class="table">';
echo '<thead>';
echo '<tr>';
echo '<th scope="col">Numéro de commande</th>';
echo '<th scope="col">Date de la commande</th>';
echo '<th scope="col">Statut de la commande</th>';
echo '<th scope="col">Montant total</th>';
echo '<th scope="col">Détails</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody class="text-white">';
foreach ($orders as $order) {
    echo '<tr>';
    echo '<td>' . $order['OrderNumber'] . '</td>';
    echo '<td>' . $order['OrderDate'] . '</td>';
    echo '<td>' . $order['Status'] . '</td>';
    echo '<td>' . $order['TotalCost'] . '€</td>';
    echo '<td><a href="orderDetails.php?id_Order=' . $order['id_order'] . '" class="btn btn-primary">Détails</a></td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

include 'footer.php';
