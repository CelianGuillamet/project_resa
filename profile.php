<?php

include 'header.php';

$idUser = getIdUser($pdo, $_SESSION['username']);

$sql = <<<SQL
SELECT *
FROM Users
WHERE id_User = :id_User
SQL;

$query = $pdo->prepare($sql);
$query->bindValue(':id_User', $idUser['id_User']);
$query->execute();
$users = $query->fetchAll(PDO::FETCH_ASSOC);

echo '<div class="container">';
echo '<div class="row">';
echo '<div class="col-md-12">';
echo '<div class="card">';
echo '<div class="card-header">';
echo '<h3>Profil de ' . $users[0]['UserName'] . '</h3>';
echo '</div>';
echo '<div class="card-body">';
echo '<div class="row">';
echo '<div class="col-md-6">';
echo '<h4 class="card-title">Nom : ' . $users[0]['LastName'] . '</h4>';
echo '<p class="card-text">Prénom : ' . $users[0]['FirstName'] . '</p>';
echo '<p class="card-text">Email : ' . $users[0]['Email'] . '</p>';
echo '<p class="card-text">Adresse : ' . $users[0]['Address'] . '</p>';
echo '</div>';
echo '<div class="text-right col-md-6">';
echo '<a href="editProfile.php?id_User=' . $users[0]['id_User'] . '" class="btn btn-primary">Modifier</a>';
echo '<a href="historic.php?id_User=' . $users[0]['id_User'] . '" class="btn btn-primary">Historique des commandes</a>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
