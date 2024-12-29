<?php
session_start();
require_once 'database.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['skills'])) {
    $user_id = $_SESSION['user_id'];
    $skills = $_POST['skills'];

    try {
        foreach ($skills as $skill) {
            // Vérifiez si la compétence existe déjà
            $stmt = $pdo->prepare("SELECT id FROM skills WHERE name = :name");
            $stmt->execute(['name' => $skill]);
            $skillData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($skillData) {
                $skill_id = $skillData['id'];
            } else {
                // Insérez une nouvelle compétence
                $stmt = $pdo->prepare("INSERT INTO skills (name) VALUES (:name)");
                $stmt->execute(['name' => $skill]);
                $skill_id = $pdo->lastInsertId();
            }

            // Associez la compétence à l'étudiant
            $stmt = $pdo->prepare("INSERT INTO student_skills (student_id, skill_id) VALUES (:student_id, :skill_id)");
            $stmt->execute(['student_id' => $user_id, 'skill_id' => $skill_id]);
        }

        header("Location: student_profile.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de l'ajout des compétences : " . htmlspecialchars($e->getMessage()));
    }
} else {
    header("Location: student_profile.php?error=1");
    exit();
}
?>
