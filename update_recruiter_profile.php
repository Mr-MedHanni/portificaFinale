<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à jour le profil recruteur</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        /* Styles généraux */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f3f5;
            margin: 0;
            padding: 0;
            color: #495057;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        main {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #007bff;
            font-size: 1.8em;
            margin-bottom: 30px;
        }

        /* Formulaire */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-weight: 600;
            font-size: 1.1em;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            padding: 12px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            padding: 12px;
            font-size: 1.1em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Messages de succès et d'erreur */
        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1.1em;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1.1em;
        }

        /* Lien de retour */
        a {
            text-align: center;
            display: block;
            color: #007bff;
            font-weight: 600;
            text-decoration: none;
            margin-top: 20px;
            font-size: 1.1em;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<main>
    <?php
    // Mise à jour du profil recruteur
    session_start();
    require_once 'database.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'recruiter') {
        header("Location: login.php");
        exit();
    }

    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $company_name = trim($_POST['company_name']);
        $position = trim($_POST['position']);

        if (empty($name) || empty($email) || empty($company_name) || empty($position)) {
            $error = "Tous les champs sont obligatoires.";
        } else {
            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :user_id");
                $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'user_id' => $_SESSION['user_id']
                ]);

                $stmt = $pdo->prepare("UPDATE recruiters SET company_name = :company_name, position = :position WHERE user_id = :user_id");
                $stmt->execute([
                    'company_name' => $company_name,
                    'position' => $position,
                    'user_id' => $_SESSION['user_id']
                ]);

                $pdo->commit();
                $success = "Votre profil a été mis à jour avec succès.";
            } catch (PDOException $e) {
                $pdo->rollBack();
                $error = "Erreur lors de la mise à jour du profil : " . $e->getMessage();
            }
        }
    }

    if ($error) {
        echo "<div class='error'>$error</div>";
    }

    if ($success) {
        echo "<div class='success'>$success</div>";
    }
    ?>

    <form action="update_recruiter_profile.php" method="POST">
        <label for="name">Nom</label>
        <input type="text" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>

        <label for="company_name">Nom de l'entreprise</label>
        <input type="text" id="company_name" name="company_name" value="<?php echo isset($company_name) ? $company_name : ''; ?>" required>

        <label for="position">Poste</label>
        <input type="text" id="position" name="position" value="<?php echo isset($position) ? $position : ''; ?>" required>

        <input type="submit" value="Mettre à jour">
    </form>

    <a href="recruiter_profile.php">Retour au profil</a>
</main>

</body>
</html>
