<?php
session_start();
require '../config/database.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant') { header("Location: ../auth/login.php"); exit; }

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: dashboard.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$id]);
$quiz = $stmt->fetch();
$cat_id = $quiz['id_category'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE quizzes SET title = ? WHERE id = ?");
    $stmt->execute([$_POST['title'], $id]);
    header("Location: manage_quizzes.php?cat_id=$cat_id"); exit;
}

require '../includes/header.php';
?>

<div class="max-w-md mx-auto bg-white p-8 rounded-xl shadow-lg mt-10 border-t-4 border-blue-500">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Modifier le Quiz</h2>
    <form method="POST">
        <label class="block text-gray-700 font-bold mb-2">Titre du Quiz</label>
        <input type="text" name="title" value="<?= htmlspecialchars($quiz['title']) ?>" required class="w-full border-2 border-gray-200 p-3 rounded-lg focus:outline-none focus:border-blue-500 transition mb-6">
        
        <div class="flex justify-between gap-4">
            <a href="manage_quizzes.php?cat_id=<?= $cat_id ?>" class="flex-1 text-center py-3 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition font-bold">Annuler</a>
            <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 shadow-md transition font-bold">Mettre à jour</button>
        </div>
    </form>
</div>

<?php require '../includes/footer.php'; ?>