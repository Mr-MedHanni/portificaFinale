<?php
// recruiter_profile.php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'recruiter') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT u.name, u.email, r.company_name, r.position FROM users u JOIN recruiters r ON u.id = r.user_id WHERE u.id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $recruiter = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des informations du profil : " . $e->getMessage();
}

$pageTitle = "TalentMatcher UPF - Profil Recruteur";
include 'header.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    main {
        max-width: 800px;
        margin: 30px auto;
        padding: 20px;
        background: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    h2 {
        text-align: center;
        color: #333333;
    }

    h3 {
        margin-bottom: 20px;
        color: #555555;
    }

    p {
        font-size: 16px;
        margin: 10px 0;
    }

    strong {
        color: #333333;
    }

    form {
        margin-top: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #555555;
    }

    input[type="text"],
    input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #cccccc;
        border-radius: 5px;
        font-size: 16px;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: #ffffff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .error {
        color: red;
        margin-top: 20px;
        font-weight: bold;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>

<main>
    <h2>Profil Recruteur</h2>
    
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php else: ?>
        <h3>Vos informations</h3>
        <p><strong>Nom :</strong> <?php echo htmlspecialchars($recruiter['name'] ?? ''); ?></p>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($recruiter['email'] ?? ''); ?></p>
        <p><strong>Entreprise :</strong> <?php echo htmlspecialchars($recruiter['company_name'] ?? ''); ?></p>
        <p><strong>Poste :</strong> <?php echo htmlspecialchars($recruiter['position'] ?? ''); ?></p>
        
        <h3>Modifier votre profil</h3>
        <form action="update_recruiter_profile.php" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($recruiter['name'] ?? ''); ?>" required>
            
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($recruiter['email'] ?? ''); ?>" required>
            
            <label for="company_name">Entreprise :</label>
            <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($recruiter['company_name'] ?? ''); ?>" required>
            
            <label for="position">Poste :</label>
            <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($recruiter['position'] ?? ''); ?>" required>
            
            <input type="submit" value="Mettre à jour le profil">
        </form>
    <?php endif; ?>
    
    <p style="text-align: center;"><a href="dashboard.php">Retour au tableau de bord</a></p>
</main>

<?php include 'footer.php'; ?>
