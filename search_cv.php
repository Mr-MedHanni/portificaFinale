<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentMatcher UPF - Recherche de CV</title>
    <style>
        /* Page globale */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
        }

        /* Conteneur principal */
        main {
            flex: 1;
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Titre principal */
        h2 {
            font-size: 1.8rem;
            color: #2a2a72;
            margin-bottom: 20px;
        }

        /* Champs du formulaire */
        .search-form form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            color: #333;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 12px;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Résultats */
        .search-results {
            text-align: left;
            margin-top: 20px;
        }

        .cv-list {
            list-style: none;
            padding: 0;
        }

        .cv-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .cv-item h4 {
            margin: 0;
            font-size: 1.2rem;
            color: #333;
        }

        .cv-item p {
            margin: 5px 0;
            color: #555;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .no-cv {
            color: #999;
        }

        /* Pied de page */
        footer {
            background-color: #2a2a72;
            color: black;
            text-align: center;
            padding: 10px 0;
            font-size: 0.9rem;
            margin-top: auto; /* Pousse le footer en bas */
        }

        footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
<?php
// search_cv.php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'recruiter') {
    header("Location: login.php");
    exit();
}

$pageTitle = "TalentMatcher UPF - Recherche de CV";
include 'header.php';

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$skill = isset($_GET['skill']) ? trim($_GET['skill']) : '';

try {
    $query = "SELECT DISTINCT u.id as user_id, u.name, u.email, s.major, s.graduation_year, c.file_path 
              FROM users u 
              JOIN students s ON u.id = s.user_id 
              LEFT JOIN cvs c ON s.user_id = c.student_id";

    $params = array();
    $conditions = array();

    if (!empty($keyword)) {
        $conditions[] = "(u.name LIKE :keyword OR s.major LIKE :keyword)";
        $params[':keyword'] = "%$keyword%";
    }

    if (!empty($skill)) {
        $conditions[] = "s.user_id IN (
            SELECT ss.student_id 
            FROM student_skills ss 
            JOIN skills sk ON ss.skill_id = sk.id 
            WHERE sk.name LIKE :skill
        )";
        $params[':skill'] = "%$skill%";
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la recherche : " . $e->getMessage();
}
?>

<main>
    <h2>Résultats de la recherche</h2>
    
    <div class="search-form">
        <form method="GET" action="search_cv.php">
            <label for="keyword">Mot-clé (nom ou filière) :</label>
            <input type="text" id="keyword" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>">
            
            <label for="skill">Compétence :</label>
            <input type="text" id="skill" name="skill" value="<?php echo htmlspecialchars($skill); ?>">
            
            <input type="submit" value="Rechercher">
        </form>
    </div>

    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php elseif (empty($results)): ?>
        <p>Aucun résultat trouvé.</p>
    <?php else: ?>
        <div class="search-results">
            <h3>Résultats trouvés : <?php echo count($results); ?></h3>
            <ul class="cv-list">
            <?php foreach ($results as $result): ?>
                <li class="cv-item">
                    <h4><?php echo htmlspecialchars($result['name']); ?></h4>
                    <p><strong>Email :</strong> <?php echo htmlspecialchars($result['email']); ?></p>
                    <p><strong>Filière :</strong> <?php echo htmlspecialchars($result['major']); ?></p>
                    <?php if ($result['graduation_year']): ?>
                        <p><strong>Année de diplôme :</strong> <?php echo htmlspecialchars($result['graduation_year']); ?></p>
                    <?php endif; ?>
                    <?php if ($result['file_path']): ?>
                        <p><a href="view_cv.php?student_id=<?php echo $result['user_id']; ?>" class="button">Voir le CV</a></p>
                    <?php else: ?>
                        <p class="no-cv">Pas de CV disponible</p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <p><a href="dashboard.php" class="button">Retour au tableau de bord</a></p>
</main>

<footer>
<?php include 'footer.php'; ?>
</footer>

</body>
</html>
