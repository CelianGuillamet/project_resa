<?php

function connectDB()
{
    require_once 'connect.php';
    try {
        $pdo = new \PDO(DSN, USER, PASS);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->exec('SET NAMES "utf8"');
        return $pdo;
    } catch (\PDOException $e) {
        echo 'Unable to connect to the database server: ' . $e->getMessage();
    }
}


function SelectNamefromDB($pdo, $column, $table)
{
    $query = "SELECT $column FROM $table";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}



function displayEvents($events)
{
    $currentEventId = null;

    echo '<div class="container mt-4 mx-auto text-decoration-none">';

    foreach ($events as $event) {
        if ($event['id_Events'] !== $currentEventId) {
            echo '<div class="row mb-4 "></div>';
            echo '<div class="row mb-4 mx-auto justify-content-center">';
            echo '<a href="artists.php?artiste=' . $event['ArtisteName'] . '" class="text-decoration-none">';
            echo '<div class="col-md-6">';
            echo '<div class="card text-white bg-primary">';
            echo '<img class="card-img-top" src="' . $event['urlimg'] . '" alt="Card image cap">';

            echo '<div class="card-body ">';
            echo '<h5 class="card-title">' . $event['EventName'] . ' - ' . $event['ArtisteName'] . '</h5>';
            echo '<p class="card-text">' . $event['Description'] . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
            $currentEventId = $event['id_Events'];
            echo '</div>';
        }

        echo '<div class="col-md-6 mx-auto ">';
        echo '<div class="card text-white bg-secondary">';
        echo '<div class="card-body ">';
        echo '<div class="row">';
        echo '<div class="col-md-6">';
        echo '<p class="card-text">Date: ' . $event['FormattedEventDate'] . ',<br>';
        echo 'Salle : ' . $event['VenueName'] . ', ' . $event['CityName'] . '</p>';
        echo '<p class="card-text">Prix le plus bas: ' . number_format($event['LowestPrice'], 2) . '€</p>';
        echo '</div>';
        echo '<div class="text-right col-md-6">';
        $encodedIdEventVenue = htmlspecialchars($event['id_Event_Venue'], ENT_QUOTES, 'UTF-8');
        echo '<a class="btn btn-primary mb-3" href="reserver.php?id_Event_Venue=' . $encodedIdEventVenue . '">Réserver</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
}


// select where one thing = one thing
function getEventsWhere($pdo, $data1, $data2)
{
    $sql = <<<SQL
SELECT
    E.id_Events,
    E.EventName,
    E.urlimg,
    E.Description,
    CONCAT(
        UCASE(LEFT(DATE_FORMAT(EV.EventDate, '%W'), 1)),
        LCASE(SUBSTRING(DATE_FORMAT(EV.EventDate, '%W'), 2)),
        ' ',
        DATE_FORMAT(EV.EventDate, '%e'),
        ' ',
        UCASE(LEFT(DATE_FORMAT(EV.EventDate, '%M'), 1)),
        LCASE(SUBSTRING(DATE_FORMAT(EV.EventDate, '%M'), 2)),
        ' ',
        DATE_FORMAT(EV.EventDate, '%Y %H:%i')
    ) AS FormattedEventDate,
    V.VenueName,
    C.CityName,
    EV.id_Event_Venue, 
    EV.EventDate,
    A.ArtisteName,
    (
        SELECT MIN(PCV.Price)
        FROM Placement_Categories_venue PCV
        WHERE PCV.id_Event_Venue = EV.id_Event_Venue
    ) AS LowestPrice
FROM
    Events AS E
LEFT JOIN
    Event_Venue EV ON E.id_Events = EV.id_Event
LEFT JOIN
    Venues V ON EV.id_Venue = V.id_Venue
LEFT JOIN
    Cities C ON V.id_City = C.id_City
LEFT JOIN
    Artistes A ON E.id_Artiste = A.id_Artiste
LEFT JOIN
    Events_Categories EC ON E.id_Events = EC.id_Event
LEFT JOIN
    Categories Cat ON EC.id_Category = Cat.id_Category
WHERE
    $data1 = :data2
ORDER BY
    E.id_Events, EV.EventDate;
SQL;


    $query = $pdo->prepare($sql);
    $query->bindValue(':data2', $data2);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}




//reserver

function Reserver($pdo, $data1, $data2)
{
    $sql = <<<SQL
    SELECT
    E.id_Events,
    E.EventName,
    E.Description,
    CONCAT(
        UCASE(LEFT(DATE_FORMAT(EV.EventDate, '%W'), 1)),
        LCASE(SUBSTRING(DATE_FORMAT(EV.EventDate, '%W'), 2)),
        ' ',
        DATE_FORMAT(EV.EventDate, '%e'),
        ' ',
        UCASE(LEFT(DATE_FORMAT(EV.EventDate, '%M'), 1)),
        LCASE(SUBSTRING(DATE_FORMAT(EV.EventDate, '%M'), 2)),
        ' ',
        DATE_FORMAT(EV.EventDate, '%Y %H:%i')
    ) AS FormattedEventDate,
    V.VenueName,
    C.CityName,
    EV.id_Event_Venue,
    EV.EventDate,
    A.ArtisteName,
    PC.Placement_CategoryName,
    PCV.id_Placement_Categories_venue,
    PCV.id_Event_Venue,
    PCV.id_Placement_Category,
    PCV.Price
    FROM Placement_Categories_venue AS PCV
    LEFT JOIN Event_Venue AS EV ON EV.id_Event_Venue = PCV.id_Event_Venue
    JOIN Events AS E ON E.id_Events = EV.id_Event
    LEFT JOIN Venues AS V ON V.id_Venue = EV.id_Venue
    JOIN Placement_Categories AS PC ON PC.id_Placement_category = PCV.id_Placement_category
    JOIN Cities AS C ON C.id_City = V.id_City
    JOIN Artistes AS A ON A.id_Artiste = E.id_Artiste
    WHERE $data1=:data2
SQL;


    $query = $pdo->prepare($sql);
    $query->bindValue(':data2', $data2);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function displayReserver($events, $data2)
{

    echo '<div class="container mt-4 mx-auto text-decoration-none">';

    if ($events[0]['id_Event_Venue'] == $data2) {
        echo '<div class="row mb-4 "></div>';
        echo '<div class="row mb-4 mx-auto justify-content-center">';
        echo '<a href="artists.php?artiste=' . $events[0]['ArtisteName'] . '" class="text-decoration-none">';
        echo '<div class="col-md-6">';
        echo '<div class="card text-white bg-primary">';
        echo '<img src="img/ahmed-sylla-tickets_179924_1593912_1240x480.jpg" class="card-img-top" alt="">';
        echo '<div class="card-body ">';
        echo '<h5 class="card-title">' . $events[0]['EventName'] . ' - ' . $events[0]['ArtisteName'] . '</h5>';
        echo '<p class="card-text">' . $events[0]['Description'] . '</p>';
        echo '<p class="card-text">Date: ' . $events[0]['FormattedEventDate'] . ',<br>';
        echo 'Salle : ' . $events[0]['VenueName'] . ', ' . $events[0]['CityName'] . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</a>';
        echo '</div>';

        echo '</div>';
    }

    echo '<div class="col-md-6 mx-auto ">';
    echo '<div class="card text-white bg-secondary">';
    echo '<div class="card-body ">';
    echo '<div class="row">';
    echo '<div class="col-md-6">';

    //form part
    $encodedIdEventVenue = htmlspecialchars($events[0]['id_Event_Venue'], ENT_QUOTES, 'UTF-8');
    echo '<form method="POST" action="reserver.php?id_Event_Venue=' . $encodedIdEventVenue . '">';
    echo '<fieldset class="form-group">';
    echo '<legend class="mt-4">Nos Catégories disponible</legend>';

    for ($i = 0; $i < 3; $i++) {

        echo '<div class="form-check mb-2">';
        echo '<input class="form-check-input" type="radio" name="placementPrice" id="optionsRadios' . $events[$i]['Placement_CategoryName'] . '" value="' . $events[$i]['Price'] . ',' . $i . '">';
        echo '<label class="form-check-label" for="optionsRadios' . $events[$i]['Placement_CategoryName'] . '">';
        echo $events[$i]['Placement_CategoryName'];
        echo '<p>' . $events[$i]['Price'] . '€</p>';
        echo '</label>';
        echo '</div>';
    }

    echo '</fieldset>';
?>
    <div class="form-group text-light">
        <label class="form-label mt-4">Nombres de places</label>
        <div class="form-floating mb-3 text-light">
            <input type="number" id="typeNumber" class="form-control" name="quantity" min="1" />
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="cancelOption">
            <label class="form-check-label" for="flexSwitchCheckDefault">Option Annulation 17.90€</label>
        </div>
    <?php
    echo '<button type="submit" class="btn btn-primary">Réserver</button>';
    echo '</form>';
    echo '</div>';
    echo '<div class="text-right col-md-6">';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}


function generateOrderNumber($customerId)
{
    $uniqueId = uniqid();

    $timestamp = date("YmdHis");

    $orderNumber = $timestamp . "_" . $customerId . "_" . $uniqueId;

    return $orderNumber;
}


function getIdUser($pdo, $data1)
{
    $sql = <<<SQL
    SELECT
    id_User
    FROM Users
    WHERE UserName = :username
SQL;

    $query = $pdo->prepare($sql);
    $query->bindValue(':username', $data1);
    $query->execute();
    $id_User = $query->fetch(PDO::FETCH_ASSOC);
    return $id_User;
}



function insertOrder($pdo, $totalCost, $idUsers, $orderNumber)
{
    $sql = <<<SQL
    INSERT INTO Orders (Status, OrderDate, TotalCost, OrderNumber, id_User)
    VALUES ('En cours', NOW(), :TotalCost , :OrderNumber, :id_User)
SQL;

    $query = $pdo->prepare($sql);
    $query->bindParam(':TotalCost', $totalCost, PDO::PARAM_STR);
    $query->bindParam(':id_User', $idUsers['id_User'], PDO::PARAM_STR);
    $query->bindParam(':OrderNumber', $orderNumber, PDO::PARAM_STR);
    $query->execute();
    $id_Order = $pdo->lastInsertId();

    return $id_Order;
}




// orderdetails


function getOrderDetails($pdo, $idOrder)
{
    $sql = <<<SQL
    SELECT *
    FROM Orders
    JOIN Order_products ON Orders.id_order = Order_products.id_order
    JOIN Placement_Categories_venue ON Order_products.id_Placement_Categories_venue = Placement_Categories_venue.id_Placement_Categories_venue
    JOIN Event_Venue ON Placement_Categories_venue.id_Event_Venue = Event_Venue.id_Event_Venue
    JOIN Events ON Event_Venue.id_Event = Events.id_Events
    JOIN Artistes ON Events.id_Artiste = Artistes.id_Artiste
    JOIN Venues ON Event_Venue.id_Venue = Venues.id_Venue
    JOIN Cities ON Venues.id_City = Cities.id_City
    JOIN Placement_Categories on Placement_Categories_venue.id_Placement_Category = Placement_Categories.id_Placement_Category
    WHERE Orders.id_order = :id_Order
    SQL;

    $query = $pdo->prepare($sql);
    $query->bindValue(':id_Order', $idOrder);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}



function displayOrderDetails($order)
{
    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<div class="card">';
    echo '<div class="card-header">';
    echo '<h3>Commande n°' . $order[0]['OrderNumber'] . '</h3>';
    echo '</div>';
    echo '<div class="card-body">';
    echo '<div class="row">';
    echo '<div class="col-md-6">';
    echo '<h4 class="card-title">Date de la commande : ' . $order[0]['OrderDate'] . '</h4>';
    echo '<p> Artiste : ' . $order[0]['ArtisteName'] . '</p>';
    echo '<p> Lieu : ' . $order[0]['VenueName'] . '</p>';
    echo '<p> Date : ' . $order[0]['EventDate'] . '</p>';
    echo '<p> Catégorie : ' . $order[0]['Placement_CategoryName'] . '</p>';
    echo '<p> Quantité : ' . $order[0]['nbPlaces'] . '</p>';
    echo '<p> Prix : ' . $order[0]['Price'] . '€</p>';
    echo '<p> Montant total : ' . $order[0]['TotalCost'] . '€</p>';
    echo '<p> Numéro de commande : ' . $order[0]['OrderNumber'] . '</p>';
    echo '</div>';
    echo '<div class="text-right col-md-6">';
    if ($order[0]['Status'] === 'En cours') {
        echo '<a href="ValidateOrder.php?id_Order=' . $order[0]['id_order'] . '" class="btn btn-primary">Valider</a>';
        echo '<br><br> ';
        echo '<a href="CancelOrder.php?id_Order=' . $order[0]['id_order'] . '" class="btn btn-primary">Annuler</a>';
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
