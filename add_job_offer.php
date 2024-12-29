<?php
// add_job_offer.php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'recruiter') {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $requirements = trim($_POST['requirements']);

    if (empty($title) || empty($description) || empty($requirements)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO job_offers (recruiter_id, title, description, requirements) VALUES (:recruiter_id, :title, :description, :requirements)");
            $stmt->execute([
                'recruiter_id' => $_SESSION['user_id'],
                'title' => $title,
                'description' => $description,
                'requirements' => $requirements
            ]);
            $success = "L'offre d'emploi a été ajoutée avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout de l'offre d'emploi : " . $e->getMessage();
        }
    }
}

$pageTitle = "TalentMatcher UPF - Ajouter une offre d'emploi";
include 'header.php';
?>

<main>
    <h2>Ajouter une offre d'emploi</h2>
    
    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>
    
    <form action="add_job_offer.php" method="POST">
        <label for="title">Titre de l'offre :</label>
        <input type="text" id="title" name="title" required><br>
        
        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea><br>
        
        <label for="requirements">Prérequis :</label>
        <textarea id="requirements" name="requirements" required></textarea><br>
        
        <input type="submit" value="Publier l'offre">
    </form>
    
    <p><a href="dashboard.php">Retour au tableau de bord</a></p>
</main>

<?php include 'footer.php'; ?>