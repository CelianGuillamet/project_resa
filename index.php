<?php
include 'header.php';

if (isset($_GET['search'])) {

  $search = ucfirst($_GET['search']);
  $sql = <<<SQL
  SELECT * FROM Events
  JOIN Artistes on Artistes.id_Artiste = Events.id_Artiste
  WHERE EventName LIKE '%$search%'
  OR Description LIKE '%$search%'
  OR ArtisteName LIKE '%$search%';
  SQL;
} else {
    

$sql = <<<SQL
SELECT * FROM Events
JOIN Artistes on Artistes.id_Artiste = Events.id_Artiste;
SQL;
}

$query = $pdo->prepare($sql);
$query->execute();
$events = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<div id="carouselBasicExample" class="carousel slide carousel-fade" data-mdb-ride="carousel">

  <div class="carousel-indicators">
    <button
      type="button"
      data-mdb-target="#carouselBasicExample"
      data-mdb-slide-to="0"
      class="active"
      aria-current="true"
      aria-label="Slide 1"
    ></button>
    <button
      type="button"
      data-mdb-target="#carouselBasicExample"
      data-mdb-slide-to="1"
      aria-label="Slide 2"
    ></button>
    <button
      type="button"
      data-mdb-target="#carouselBasicExample"
      data-mdb-slide-to="2"
      aria-label="Slide 3"
    ></button>
  </div>


  <div class="carousel-inner">
    
    <div class="carousel-item active">
      <img src="img/djadja---dinaz---tourn-e-tickets_176292_1581747_1240x480.jpg" class="d-block w-100" alt="Sunset Over the City"/>
      <div class="carousel-caption d-none d-md-block">
        <h5>Djadja & Dinaz</h5>
        <p>Tournée</p>
      </div>
    </div>

    
    <div class="carousel-item">
      <img src="img/christophe-ma--carnet-de-voyage--tourn-e--tickets_145750_1314762_1240x480.jpg" class="d-block w-100" alt="Canyon at Nigh"/>
      <div class="carousel-caption d-none d-md-block">
        <h5>Christophe Mae</h5>
        <p>Carnet de Voyage - Tournée</p>
      </div>
    </div>

    
    <div class="carousel-item">
      <img src="img/ahmed-sylla-tickets_179924_1593912_1240x480.jpg" class="d-block w-100" alt="Cliff Above a Stormy Sea"/>
      <div class="carousel-caption d-none d-md-block">
        <h5>Ahmed Sylla </h5>
        <p>Origami - Tournée</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="prev">
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="next">
    <span class="visually-hidden">Next</span>
  </button>

</div>

<?php
 echo '<div class="container mt-4">';
 echo '<div class="row">';
 
 foreach ($events as $event) {
     echo '<div class="col-md-4 mb-4">';
     echo '<div class="card" style="width: 18rem;">';
     echo '<img class="card-img-top" src="' . $event['urlimg']. '" alt="Card image cap">';
     echo '<div class="card-body">';
     echo '<h5 class="card-title">' . $event['EventName'] . '</h5>';
     echo '<h6 class="card-subtitle mb-2 text-muted">' . $event['ArtisteName'] . '</h6>';
     echo '<p class="card-text">' . $event['Description'] . '</p>';
     echo '<a href="artists.php?artiste=' . $event['ArtisteName'] . '" class="btn btn-primary">';
     echo 'Voir l\'artiste</a>';
     echo '</div>';
     echo '</div>';
     echo '</div>';
 }
 
 echo '</div>';
 echo '</div>';
 



?>

<?php
include 'footer.php';
