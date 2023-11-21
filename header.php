<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.3.2/solar/bootstrap.css">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <title>Page d'accueil</title>
</head>

<body>
  <?php
  include 'functions.php';
  $pdo = connectDB();
  $pdo->exec("SET lc_time_names = 'fr_FR';");
  $categories = SelectNamefromDB($pdo, 'categoryName', 'categories');
  $cities = SelectNamefromDB($pdo, 'CityName', 'cities');
  ?>


  <?php

  ?>
  <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container-fluid">

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Home
              <span class="visually-hidden">(current)</span>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Catégories</a>
            <div class="dropdown-menu">
              <?php
              foreach ($categories as $category) : ?>
                <a class="dropdown-item" href="categories.php?category=<?php echo $category ?>"><?php echo $category ?></a>
              <?php endforeach ?>
            </div>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Villes</a>
            <div class="dropdown-menu">
              <?php
              foreach ($cities as $city) : ?>
                <a class="dropdown-item" href="city.php?id=<?php echo $city ?>"><?php echo $city ?></a>
              <?php endforeach ?>
            </div>
          </li>
        </ul>
        <form class="d-flex mx-auto">
          <input class="form-control me-sm-2" type="search" placeholder="Search" name="search">
          <button class="btn btn-secondary my-2 my-sm-0" type="submit">Rechercher</button>
        </form>
      </div>
    </div>
    <?php if (isset($_SESSION['username'])) : ?>
      <p class="text-white">Bonjour <?php echo $_SESSION['username'] ?></p>
      <a href="logout.php" class="btn btn-outline-light me-2">Logout</a>
      <a href="profile.php" class="btn btn-outline-light me-2">Profile</a>
      <a href="cart.php" class="btn btn-outline-light me-2">Panier</a>
    <?php else : ?>
      <a href="login.php" class="btn btn-outline-light me-2">Login</a>
      <a href="signup.php" class="btn btn-primary btn-outline-light">Sign-up</a>
    <?php endif ?>

  </nav>