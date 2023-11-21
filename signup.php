<?php

include 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST['password']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $address = htmlspecialchars($_POST['address']);

    if (empty($username) || empty($password) || empty($firstname) || empty($lastname) || empty($address)) {
        echo '<div class="alert alert-danger" role="alert">Veuillez remplir tous les champs</div>';
    } else {
        if (strlen($password) < 6) {
            echo '<div class="alert alert-danger" role="alert">Le mot de passe doit contenir au moins 6 caractères</div>';
            exit();
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO Users (UserName, Email, Password, FirstName, LastName, Address) VALUES (:username, :email, :hashed, :firstname, :lastname, :address)");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':hashed', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->execute();
            echo '<div class="alert alert-success" role="alert">Votre compte a bien été créé</div>';
        }
    }
}

?>

<div class="container mt-5">
    <h2 class="mb-4">Sign Up</h2>
    <form action="signup.php" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="form-group">
            <label for="firstname">First Name:</label>
            <input type="text" class="form-control" name="firstname" required>
        </div>

        <div class="form-group">
            <label for="lastname">Last Name:</label>
            <input type="text" class="form-control" name="lastname" required>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" class="form-control" name="address" required>
        </div>

        <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>
</div>
<?php

include 'footer.php';
