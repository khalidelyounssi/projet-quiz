<?php
require '../config/database.php';
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }

if(!isset($_GET['id'])) { header("Location: all_quizzes.php"); exit; }
$id = $_GET['id'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $stmt = $pdo->prepare("UPDATE quizzes SET title = ?, id_category = ? WHERE id = ?");
    $stmt->execute([$_POST['title'], $_POST['category_id'], $id]);
    header("Location: all_quizzes.php"); // نرجعو للائحة
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$id]);
$quiz = $stmt->fetch();

$stmtCat = $pdo->prepare("SELECT * FROM categories WHERE id_enseignant = ?");
$stmtCat->execute([$_SESSION['user_id']]);
$categories = $stmtCat->fetchAll();

require '../includes/header.php';
?>

<div class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow-lg mt-10 border-t-4 border-blue-500">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Modifier le Quiz</h2>
    
    <form method="POST">
        <div class="mb-4">
            <label class="block font-bold mb-2 text-gray-700">Titre du Quiz</label>
            <input type="text" name="title" value="<?= htmlspecialchars($quiz['title']) ?>" class="w-full border p-3 rounded-lg bg-gray-50 focus:bg-white transition outline-none focus:ring-2 focus:ring-blue-200">
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-2 text-gray-700">Catégorie</label>
            <select name="category_id" class="w-full border p-3 rounded-lg bg-white outline-none focus:ring-2 focus:ring-blue-200">
                <?php foreach($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $quiz['id_category'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex gap-4">
            <a href="all_quizzes.php" class="flex-1 text-center py-3 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition font-bold">Annuler</a>
            <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 shadow-lg transition">Enregistrer</button>
        </div>
    </form>
</div>

<?php require '../includes/footer.php'; ?>