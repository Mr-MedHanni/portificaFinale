<?php
// auth.php
require_once 'database.php';

/**
 * Authentifie un utilisateur en fonction de son email et de son mot de passe.
 *
 * @param string $email L'email de l'utilisateur.
 * @param string $password Le mot de passe de l'utilisateur.
 * @return array|false Les données utilisateur si l'authentification réussit, sinon false.
 */
function authenticateUser($email, $password) {
    global $pdo;

    try {
        // Préparation de la requête SQL pour éviter les injections
        $stmt = $pdo->prepare("SELECT id, name, email, password, user_type FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        // Récupération des résultats
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe et retour de l'utilisateur
        if ($user && password_verify($password, $user['password'])) {
            return [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'user_type' => $user['user_type']
            ];
        }

        return false;
    } catch (PDOException $e) {
        // Enregistrement de l'erreur dans un fichier de log
        error_log("Erreur d'authentification : " . $e->getMessage(), 3, 'errors.log');
        return false;
    }
}
