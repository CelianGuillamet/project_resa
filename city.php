<?php
include 'header.php';
$cityName = isset($_GET['id']) ? $_GET['id'] : '';
?>

<div class="p-5 text-center bg-image rounded-3" style="
    background-image: url('img/fond-ville.jpeg');
    height: 300px;
  ">
  <div class="mask">
    <div class="d-flex justify-content-center align-items-center h-100">
      <div class="text-black">
        <h1 class="mb-2 text-light" style="font-size: 8em;"><strong><?php echo $_GET['id']?></strong></h1>
      </div>
    </div>
  </div>
</div>
<?php

$cityName = isset($_GET['id']) ? $_GET['id'] : '';
$pdo->exec("SET lc_time_names = 'fr_FR';");


$cities = getEventsWhere($pdo, 'C.CityName', $cityName);

displayEvents($cities);


include 'footer.php';
?>

