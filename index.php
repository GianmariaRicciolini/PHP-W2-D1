<?php
include __DIR__ . '/includes/dbconnect.php';

$errors = [];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container mt-4">
    <div class="row">
        <div class="col-6">
        <form action="" method="post" class="pt-5 d-flex flex-column" novalidate>
                <div class="row mb-3">
                    <label class="col-4 pt-3" for="username">Username</label>
                    <div class="col-8">
                        <input class="form-control mt-2 w-100" type="text" name="username" id="username" placeholder="Username">
                    </div>
                </div>
        
                <div class="row mb-3">
                    <label class="col-4 pt-3" for="email">Email</label>
                    <div class="col-8">
                    <input class="form-control mt-2 w-100" type="email" name="email" id="email" placeholder="example@email.com" value="">
                    </div>
                </div>
        
                <div class="row mb-3">
                    <label class="col-4 pt-3" for="password">Password</label>
                    <div class="col-8">
                        <input type="password" name="password" id="password" placeholder="A secure password" class="form-control mt-2 w-100">
                    </div>
                </div>
        
                <button type="submit" class="btn btn-primary w-25 mt-4">Invia</button>
            </form>
        </div>
        <div class="col-6">
        <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validazione dei dati
    if (empty($username)) {
        $errors['username'] = 'Inserisci un username';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email non valida';
    }

    if (strlen($password) < 8) {
        $errors['password'] = 'Password troppo corta';
    }

     // Controlla se l'email o l'username sono già presenti nel database
     $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? OR username = ?');
     $stmt->execute([$email, $username]);
     $existingUser = $stmt->fetch();
 
     if ($existingUser) {
         if ($existingUser['email'] === $email) {
             $errors['email'] = 'Questo indirizzo email è già registrato';
         }
         if ($existingUser['username'] === $username) {
             $errors['username'] = 'Questo username è già utilizzato';
         }
     }
 
    // Se ci sono errori, visualizza il form con gli errori
    if (!empty($errors)) {
        echo '<div class="container mt-4 pt-5">';
        echo '<div class="alert alert-danger" role="alert">';
        echo '<h4 class="alert-heading">Ci sono errori nel modulo:</h4>';
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
        echo '</div>';
        echo '</div>';
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, $hashed_password]);

        echo '<div class="container mt-4 pt-5">';
        echo '<div class="alert alert-success" role="alert">';
        echo '<h4 class="alert-heading">User Aggiunto!:</h4>';
        echo '</div>';
        echo '</div>';
        exit;
    }
}
?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>