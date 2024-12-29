<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>form {
    display: flex;
    flex-direction: column;
    max-width: 500px;
    margin: 0 auto;
}

form label {
    margin: 10px 0 5px;
    font-weight: bold;
}

form input[type="text"],
form input[type="email"],
form input[type="number"] {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 10px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

.success {
    color: green;
    font-weight: bold;
}

.error {
    color: red;
    font-weight: bold;
}
</style>
</head>
<body>
<?php
// update_profile.php
session_start();
require_once 'database.php';

// Vérifier si l'utilisateur est connecté et est un étudiant
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $major = $_POST['major'];
    $graduation_year = $_POST['graduation_year'];

    try {
        // Mise à jour de la table users
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :user_id");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'user_id' => $user_id
        ]);

        // Mise à jour de la table students
        $stmt = $pdo->prepare("UPDATE students SET major = :major, graduation_year = :graduation_year WHERE user_id = :user_id");
        $stmt->execute([
            'major' => $major,
            'graduation_year' => $graduation_year,
            'user_id' => $user_id
        ]);

        $success_message = "Profil mis à jour avec succès !";
    } catch (PDOException $e) {
        $error_message = "Erreur lors de la mise à jour du profil : " . $e->getMessage();
    }
}

// Récupération des informations actuelles de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT u.name, u.email, s.major, s.graduation_year FROM users u JOIN students s ON u.id = s.user_id WHERE u.id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Erreur lors de la récupération des informations du profil : " . $e->getMessage();
}

$pageTitle = "TalentMatcher UPF - Mise à jour du profil";
include 'header.php';
?>

<main>
    <h2>Mise à jour du profil</h2>
    
    <?php if ($success_message): ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="name">Nom complet :</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_info['name']); ?>" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_info['email']); ?>" required>

        <label for="major">Filière :</label>
        <input type="text" id="major" name="major" value="<?php echo htmlspecialchars($user_info['major']); ?>">

        <label for="graduation_year">Année de diplôme :</label>
        <input type="number" id="graduation_year" name="graduation_year" value="<?php echo htmlspecialchars($user_info['graduation_year']); ?>">

        <input type="submit" value="Mettre à jour le profil">
    </form>

    <p><a href="student_profile.php">Retour au profil</a></p>
</main>

<?php include 'footer.php'; ?>
</body>
</html>