<?php
include 'header.php';
?>
<div class="p-5 text-center bg-image rounded-3" style="
    background-image: url('img/fond-cat.jpeg');
    height: 300px;
  ">
  <div class="mask">
    <div class="d-flex justify-content-center align-items-center h-100">
      <div class="text-black">
        <h1 class="mb-2 text-light" style="font-size: 6em;"><strong><?php echo $_GET['artiste'] ?></strong></h1>
      </div>
    </div>
  </div>
</div>
<?php
$artisteName = isset($_GET['artiste']) ? $_GET['artiste'] : '';
$pdo->exec("SET lc_time_names = 'fr_FR';");

$artistes = getEventsWhere($pdo, 'A.ArtisteName', $artisteName);

displayEvents($artistes);

include 'footer.php';
?>