<?php
// edit_job_offer.php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'recruiter') {
    header("Location: login.php");
    exit();
}

$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($job_id === 0) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

try {
    $stmt = $pdo->prepare("SELECT * FROM job_offers WHERE id = :job_id AND recruiter_id = :recruiter_id");
    $stmt->execute(['job_id' => $job_id, 'recruiter_id' => $_SESSION['user_id']]);
    $job_offer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job_offer) {
        header("Location: dashboard.php");
        exit();
    }
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération de l'offre d'emploi : " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $requirements = trim($_POST['requirements']);

    if (empty($title) || empty($description) || empty($requirements)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE job_offers SET title = :title, description = :description, requirements = :requirements WHERE id = :job_id AND recruiter_id = :recruiter_id");
            $stmt->execute([
                'title' => $title,
                'description' => $description,
                'requirements' => $requirements,
                'job_id' => $job_id,
                'recruiter_id' => $_SESSION['user_id']
            ]);
            $success = "L'offre d'emploi a été mise à jour avec succès.";
            $job_offer['title'] = $title;
            $job_offer['description'] = $description;
            $job_offer['requirements'] = $requirements;
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour de l'offre d'emploi : " . $e->getMessage();
        }
    }
}

$pageTitle = "TalentMatcher UPF - Modifier une offre d'emploi";
include 'header.php';
?>

<main>
    <h2>Modifier une offre d'emploi</h2>
    
    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>
    
    <form action="edit_job_offer.php?id=<?php echo $job_id; ?>" method="POST">
        <label for="title">Titre de l'offre :</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($job_offer['title']); ?>" required><br>
        
        <label for="description">Description :</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($job_offer['description']); ?></textarea><br>
        
        <label for="requirements">Prérequis :</label>
        <textarea id="requirements" name="requirements" required><?php echo htmlspecialchars($job_offer['requirements']); ?></textarea><br>
        
        <input type="submit" value="Mettre à jour l'offre">
    </form>
    
    <p><a href="view_job_offer.php?id=<?php echo $job_id; ?>">Retour aux détails de l'offre</a></p>
    <p><a href="dashboard.php">Retour au tableau de bord</a></p>
</main>

<?php include 'footer.php'; ?>