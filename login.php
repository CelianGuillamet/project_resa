<?php


include 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_username = htmlspecialchars($_POST["username"]);
    $entered_password = htmlspecialchars($_POST["password"]);


    $stmt = $pdo->prepare("SELECT Password FROM Users WHERE UserName = :username");
    $stmt->bindParam(':username', $entered_username, PDO::PARAM_STR);
    $stmt->execute();


    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $stored_hashed_password = $result['Password'];

        if (password_verify($entered_password, $stored_hashed_password)) {
            echo "Login successful";
            $_SESSION['username'] = $entered_username;
            header('Location: /index.php');
        } else {
            echo "Incorrect password";
        }
    } else {
        echo "Username not found";
    }
}

?>

<div class="container mt-5">
    <h2 class="mb-4">Login</h2>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<?php

include 'footer.php';

?>