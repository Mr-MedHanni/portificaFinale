<?php
// login.php
session_start();
require_once 'auth.php';

$pageTitle = "TalentMatcher UPF - Connexion";
include 'header.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = authenticateUser($email, $password);

    var_dump($user);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['user_name'] = $user['name'];

        if ($user['user_type'] == 'recruiter') {
            header("Location: recruiter_dashbord.php");
        } elseif ($user['user_type'] == 'student') {
            header("Location: student_dashboard.php");
        } else {
            header('location: admin_dashboard.php');
        }
        exit();
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - TalentMatcher</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Centering the form */
        body {
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;

            
        }

        main {
            width: 100%;
            max-width: 400px;
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #333;
        }

        .error {
            color: #e74c3c;
            margin-bottom: 1rem;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            text-align: left;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #555;
        }

        input[type="email"],
        input[type="password"] {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.75rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <center>
        <main>
            <h2>Connexion</h2>
            <?php
            // Display error if any
            if (!empty($error)) echo "<p class='error'>$error</p>";
            ?>
            <form method="POST" action="">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Entrez votre email" required>

                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>

                <input type="submit" value="Se connecter">
            </form>
            <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
        </main>
    </center>
</body>

</html>



<?php include 'footer.php'; ?>