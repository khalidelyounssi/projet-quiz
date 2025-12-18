<?php
session_start();
require '../config/database.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $role = $_POST['role'];

    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if($check->rowCount() > 0){
        $msg = "Cet email existe déjà !";
    } else {
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if($stmt->execute([$user, $email, $pass, $role])){
            header("Location: login.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Inscription</h2>
        <?php if($msg): ?><p class="text-red-500 text-center mb-4"><?=$msg?></p><?php endif; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Nom complet" required class="w-full border p-2 mb-4 rounded">
            <input type="email" name="email" placeholder="Email" required class="w-full border p-2 mb-4 rounded">
            <input type="password" name="password" placeholder="Mot de passe" required class="w-full border p-2 mb-4 rounded">
            <select name="role" class="w-full border p-2 mb-4 rounded bg-white">
                <option value="etudiant">Étudiant</option>
                <option value="enseignant">Enseignant</option>
            </select>
            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">S'inscrire</button>
        </form>
        <a href="login.php" class="block text-center mt-4 text-blue-500">Se connecter</a>
    </div>
</body>
</html>