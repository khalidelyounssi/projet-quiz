<?php
require '../config/database.php';
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }

$cats = $pdo->prepare("SELECT * FROM categories WHERE id_enseignant=?");
$cats->execute([$_SESSION['user_id']]);
$categories = $cats->fetchAll();

if(!$categories) {
    echo "<script>alert('Veuillez créer une catégorie d\'abord !'); window.location='categories.php';</script>";
    exit;
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $stmt = $pdo->prepare("INSERT INTO quizzes (title, id_category) VALUES (?, ?)");
    $stmt->execute([$_POST['title'], $_POST['category_id']]);
    $new_id = $pdo->lastInsertId();
    header("Location: add_question.php?quiz_id=$new_id"); exit;
}

require '../includes/header.php';
?>
<div class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow-lg mt-10">
    <h2 class="text-2xl font-bold mb-6 text-indigo-600">Créer un Quiz</h2>
    <form method="POST">
        <label class="block font-bold mb-2 text-gray-700">Titre</label>
        <input type="text" name="title" required class="w-full border p-3 rounded-lg mb-4 bg-gray-50 focus:bg-white transition outline-none focus:ring-2 focus:ring-indigo-200">
        
        <label class="block font-bold mb-2 text-gray-700">Catégorie</label>
        <select name="category_id" class="w-full border p-3 rounded-lg mb-6 bg-white outline-none focus:ring-2 focus:ring-indigo-200">
            <?php foreach($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
        
        <div class="flex gap-4">
            <a href="all_quizzes.php" class="flex-1 text-center py-3 rounded-lg border border-gray-200 text-gray-600">Annuler</a>
            <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700">Suivant</button>
        </div>
    </form>
</div>
<?php require '../includes/footer.php'; ?>