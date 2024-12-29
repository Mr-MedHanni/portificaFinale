<?php
// register.php
session_start();
require_once 'database.php';

$pageTitle = "TalentMatcher UPF - Création de compte";
include 'header.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format d'email invalide.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, user_type) VALUES (:name, :email, :password, :user_type)");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $hashed_password,
                'user_type' => $user_type
            ]);
            
            $user_id = $pdo->lastInsertId();
            
            if ($user_type == 'student') {
                $stmt = $pdo->prepare("INSERT INTO students (user_id) VALUES (:user_id)");
            } else {
                $stmt = $pdo->prepare("INSERT INTO recruiters (user_id) VALUES (:user_id)");
            }
            $stmt->execute(['user_id' => $user_id]);
            
            $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            header("refresh:3;url=login.php");
        } catch (PDOException $e) {
            $error = "Erreur lors de la création du compte : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>/* style.css */
body {
    font-family: Arial, sans-serif;
    background-color: #fff;
    margin: 0;
    padding: 0;
    color: #333;
}

main {
    width: 90%;
    max-width: 500px;
    margin: 3rem auto;
    background: #fff;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #007BFF;
    text-align: center;
    margin-bottom: 1.5rem;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-top: 1rem;
    font-weight: bold;
}

input, select {
    padding: 0.8rem;
    margin-top: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
}

input[type="submit"] {
    margin-top: 2rem;
    background-color: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 1.1rem;
    border-radius: 5px;
    padding: 0.8rem;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

p {
    text-align: center;
    margin-top: 1rem;
}

a {
    color: #007BFF;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

.error {
    color: #FF0000;
    font-weight: bold;
    text-align: center;
    margin-bottom: 1rem;
}

.success {
    color: #28a745;
    font-weight: bold;
    text-align: center;
    margin-bottom: 1rem;
}
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<main>
    <h2>Création de compte</h2>
    
    <?php
    if ($error) {
        echo "<p class='error'>$error</p>";
    }
    if ($success) {
        echo "<p class='success'>$success</p>";
    }
    ?>

    <form method="POST" action="">
        <label for="user_type">Type d'utilisateur :</label>
        <select id="user_type" name="user_type" required>
            <option value="student">Étudiant</option>
            <option value="recruiter">Recruteur</option>
        </select>

        <label for="name">Nom complet :</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <input type="submit" value="Créer un compte">
    </form>

    <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
</main>

<?php include 'footer.php'; ?>
</body>
</html>