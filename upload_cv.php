<?php
// upload_cv.php
session_start();
require_once 'database.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit();
}

$pageTitle = "TalentMatcher UPF - Télécharger un CV";
include 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["cv_file"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Vérifier si le fichier est un PDF
    if ($fileType != "pdf") {
        $error = "Désolé, seuls les fichiers PDF sont autorisés.";
        $uploadOk = 0;
    }

    // Vérifier la taille du fichier
    if ($_FILES["cv_file"]["size"] > 500000) {
        $error = "Désolé, votre fichier est trop volumineux.";
        $uploadOk = 0;
    }

    // Si tout est OK, essayer de télécharger le fichier
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["cv_file"]["tmp_name"], $target_file)) {
            $success = "Le fichier " . htmlspecialchars(basename($_FILES["cv_file"]["name"])) . " a été téléchargé avec succès.";

            try {
                // Enregistrer les informations du fichier dans la base de données
                $student_id = $_SESSION['user_id'];
                $upload_date = date("Y-m-d H:i:s");

                $stmt = $pdo->prepare("INSERT INTO cvs (student_id, file_path, upload_date) VALUES (:student_id, :file_path, :upload_date)");
                $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $stmt->bindParam(':file_path', $target_file, PDO::PARAM_STR);
                $stmt->bindParam(':upload_date', $upload_date, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $success .= " ";
                } else {
                    $error = "Erreur lors de l'enregistrement .";
                }
            } catch (PDOException $e) {
                $error = "Erreur de base de données : " . $e->getMessage();
            }
        } else {
            $error = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        main {
            max-width: 600px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 1rem;
        }

        form {
            margin-top: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        form input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        form input[type="submit"] {
            background-color: #007BFF;
            color: white;
            font-size: 1rem;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            text-align: center;
            color: #007BFF;
            text-decoration: none;
            margin-top: 1rem;
            font-size: 1rem;
            display: inline-block;
        }

        a:hover {
            color: #0056b3;
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
<main>
    <h2>Télécharger un CV</h2>

    <?php if (isset($error)): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="cv_file">Sélectionnez un fichier PDF à télécharger :</label>
        <input type="file" name="cv_file" id="cv_file" required>
        <input type="submit" value="Télécharger le CV" name="submit">
    </form>

    <a href="student_profile.php">Retour au profil</a>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
