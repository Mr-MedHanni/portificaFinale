<?php
// student_profile.php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit();
}

$pageTitle = "TalentMatcher UPF - Profil étudiant";






// Récupération des informations de l'étudiant
$user_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("SELECT u.name, u.email, s.major, s.graduation_year FROM users u JOIN students s ON u.id = s.user_id WHERE u.id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    // Remplir par défaut si certaines colonnes sont NULL
    $student['name'] = $student['name'] ?? 'Non défini';
    $student['email'] = $student['email'] ?? 'Non défini';
    $student['major'] = $student['major'] ?? '';
    $student['graduation_year'] = $student['graduation_year'] ?? '';
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des informations : " . $e->getMessage();
}

// Vérification si un CV a été téléchargé
try {
    $stmt = $pdo->prepare("SELECT id FROM cvs WHERE student_id = :student_id");
    $stmt->execute(['student_id' => $user_id]);
    $cv = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la vérification du CV : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>




    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
    .cv-section {
        margin-top: 20px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .cv-section h3 {
        margin-bottom: 15px;
        color: #007bff;
    }

    .cv-section p {
        margin-bottom: 10px;
        font-size: 16px;
        color: #333;
    }

    .cv-actions {
        display: flex;
        gap: 15px;
    }

    .cv-actions a {
        display: inline-block;
        text-decoration: none;
        color: white;
        background-color: #007bff;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
        transition: background-color 0.3s ease;
        text-align: center;
    }

    .cv-actions a:hover {
        background-color: #0056b3;
    }

    .cv-placeholder {
        font-style: italic;
        color: #888;
        margin-bottom: 10px;
    }
</style>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        main {
            width: 80%;
            margin: 2rem auto;
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            color: #007BFF;
        }

        form {
            margin-top: 1rem;
        }

        form label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
        }

        form input, form select {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            padding: 0.7rem 1.5rem;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .btn {
            padding: 8px 12px;
            font-size: 14px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-add {
            background-color: #007BFF;
        }

        .btn-add:hover {
            background-color: #0056b3;
        }

        .btn-remove {
            background-color: #FF4D4D;
            color: white;
        }

        .btn-remove:hover {
            background-color: #CC0000;
        }

        .skill-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .skill-row input {
            flex: 1;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        a:hover {
            color: #0056b3;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
    <style>
    .btn-dashboard {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        color: white;
        background-color: #28a745;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .btn-dashboard:hover {
        background-color: #218838;
    }
</style>

</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary" style="width: 100%;">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand">
            <img src="Portifica.png" alt="Portifica Logo" width="100" >
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
    </div>
</nav>
<main>
    <h2>Profil étudiant</h2>
    <p>Bienvenue sur votre profil, <?php echo htmlspecialchars($student['name']); ?>.</p>
    
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    
    <form action="update_student_profile.php" method="POST" id="profile-form">
        <h3>Vos informations</h3>
        <label for="name">Nom:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" ><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" readonly><br>
        
        <label for="major">Filière:</label>
        <input type="text" id="major" name="major" value="<?php echo htmlspecialchars($student['major']); ?>"><br>
        
        <label for="graduation_year">Année de diplôme:</label>
        <input type="number" id="graduation_year" name="graduation_year" value="<?php echo htmlspecialchars($student['graduation_year']); ?>"><br>

        <h3>Vos compétences</h3>
        <div id="skills-container">
            <?php
            $stmt = $pdo->prepare("SELECT skills.id, skills.name FROM skills 
                                    INNER JOIN student_skills ON skills.id = student_skills.skill_id
                                    WHERE student_skills.student_id = :student_id");
            $stmt->execute(['student_id' => $user_id]);
            $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($skills)) {
                foreach ($skills as $index => $skill) {
                    echo '<div class="skill-row">';
                    echo '<input type="text" name="skills[]" id="skill_' . ($index + 1) . '" value="' . htmlspecialchars($skill['name']) . '">';
                    echo '<button type="button" class="btn btn-remove">Supprimer</button>';
                    echo '</div>';
                }
            } else {
                echo '<div class="skill-row">';
                echo '<input type="text" name="skills[]" id="skill_1">';
                echo '<button type="button" class="btn btn-remove">Supprimer</button>';
                echo '</div>';
            }
            ?>
        </div>
        <button type="button" id="add-skill" class="btn btn-add">Ajouter une compétence</button><br><br>
        
        <input type="submit" value="Mettre à jour le profil">
    </form>
    
    <div class="cv-section">
    <h3>Votre CV</h3>
    <?php if ($cv): ?>
        <p>Vous avez déjà téléchargé un CV.</p>
        <div class="cv-actions">
            <a href="view_cv.php">Voir votre CV</a>
            <a href="upload_cv.php">Mettre à jour votre CV</a>
        </div>
    <?php else: ?>
        <p class="cv-placeholder">Aucun CV téléchargé.</p>
        <div class="cv-actions">
            <a href="upload_cv.php">Télécharger un CV</a>
        </div>
    <?php endif; ?>

</div>
<div style="text-align: center; margin-top: 20px;">
        <a href="student_dashboard.php" class="btn btn-dashboard">Aller vers le tableau de bord</a>
    </div>
</main>

<script>
    document.getElementById('add-skill').addEventListener('click', function () {
        const container = document.getElementById('skills-container');
        const count = container.querySelectorAll('.skill-row').length + 1;

        const row = document.createElement('div');
        row.className = 'skill-row';
        row.innerHTML = `
            <input type="text" name="skills[]" id="skill_${count}">
            <button type="button" class="btn btn-remove">Supprimer</button>
        `;
        container.appendChild(row);

        row.querySelector('.btn-remove').addEventListener('click', function () {
            row.remove();
        });
    });

    document.querySelectorAll('.btn-remove').forEach(button => {
        button.addEventListener('click', function () {
            button.parentElement.remove();
        });
    });
</script>


</body>
</html>
