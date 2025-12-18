<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] == 'enseignant') header("Location: ../enseignant/dashboard.php");
        else header("Location: ../etudiant/dashboard.php");
        exit;
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head><script src="https://cdn.tailwindcss.com"></script></head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <form method="POST" class="bg-white p-8 rounded shadow w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">Connexion</h2>
        <?php if(isset($error)) echo "<p class='text-red-500 text-center mb-2'>$error</p>"; ?>
        <input type="email" name="email" placeholder="Email" required class="w-full border p-2 mb-4 rounded">
        <input type="password" name="password" placeholder="Mot de passe" required class="w-full border p-2 mb-4 rounded">
        <button class="w-full bg-indigo-600 text-white p-2 rounded">Se connecter</button>
        <a href="register.php" class="block text-center mt-4 text-sm text-gray-500">Créer un compte</a>
    </form>
</body>
</html>