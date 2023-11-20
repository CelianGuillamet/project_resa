<?php

include 'header.php';
$id_Event_Venue = ($_GET['id_Event_Venue']);


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['username'])) {
    $reserver = Reserver($pdo, 'PCV.id_Event_Venue', $id_Event_Venue);

    if (isset($_POST['placementPrice'])) {
        $selectedPriceAndIndex = explode(',', $_POST['placementPrice']);
        $selectedPrice = $selectedPriceAndIndex[0];
        $selectedIndex = $selectedPriceAndIndex[1];
        $id_Placement_Categories_venue = $reserver[$selectedIndex]['id_Placement_Categories_venue'];
    }


    $idUsers = getIdUser($pdo, $_SESSION['username']);
    $orderNumber = generateOrderNumber($idUsers['id_User']);
    $price = $selectedPrice = $selectedPriceAndIndex[0];
    $quantity = $_POST['quantity'];

    if (isset($price) && isset($quantity) && isset($_POST['cancelOption'])) {
        $totalCost = ($price * $quantity) + 17.90;
    } elseif (isset($price) && isset($quantity)) {
        $totalCost = $price * $quantity;
    }

    if (isset($_POST['cancelOption'])) {
        $id_option = 1;
    } else {
        $id_option = "";
    }

    $id_Order = insertOrder($pdo, $totalCost, $idUsers, $orderNumber);

    $sql = <<<SQL
    INSERT INTO Order_products (id_order, id_Placement_Categories_venue, nbPlaces, Price, id_Option)
    VALUES (:id_Order, :id_Placement_Categories_venue, :nbPlaces, :Price, :id_Option)
SQL;

    $query = $pdo->prepare($sql);
    $query->bindParam(':id_Order', $id_Order, PDO::PARAM_INT);
    $query->bindParam(':id_Placement_Categories_venue', $id_Placement_Categories_venue, PDO::PARAM_INT);
    $query->bindParam(':nbPlaces', $_POST['quantity'], PDO::PARAM_INT);
    $query->bindParam(':Price', $selectedPrice, PDO::PARAM_INT);
    $query->bindParam(':id_Option', $id_option, PDO::PARAM_STR);
    $query->execute();

    echo '<div class="alert alert-success" role="alert">Votre commande a bien été enregistrée</div>';
} else {
    if (isset($_SESSION['username'])) {
        $reserver = Reserver($pdo, 'PCV.id_Event_Venue', $id_Event_Venue);
        displayReserver($reserver, $id_Event_Venue);
    } else {
        echo '<div class="alert alert-danger" role="alert">Veuillez vous connecter pour réserver</div>';
    }
}
