<?php
session_start();
require '../config/database.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant') { header("Location: ../auth/login.php"); exit; }

if (!isset($_GET['cat_id'])) { header("Location: dashboard.php"); exit; }
$cat_id = $_GET['cat_id'];

if (isset($_GET['del_quiz'])) {
    $stmt = $pdo->prepare("DELETE FROM quizzes WHERE id = ?");
    $stmt->execute([$_GET['del_quiz']]);
    header("Location: manage_quizzes.php?cat_id=$cat_id"); exit;
}

$stmtC = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
$stmtC->execute([$cat_id]);
$catName = $stmtC->fetchColumn();

$stmtQ = $pdo->prepare("SELECT * FROM quizzes WHERE id_category = ?");
$stmtQ->execute([$cat_id]);
$quizzes = $stmtQ->fetchAll();

require '../includes/header.php';
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Catégorie: <span class="text-indigo-600"><?= htmlspecialchars($catName) ?></span></h1>
    <a href="add_quiz.php?cat_id=<?= $cat_id ?>" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
        <i class="fas fa-plus"></i> Nouveau Quiz
    </a>
</div>

<div class="bg-white shadow rounded overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-4">Titre du Quiz</th>
                <th class="p-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($quizzes as $quiz): ?>
            <tr class="border-b">
                <td class="p-4 font-bold"><?= htmlspecialchars($quiz['title']) ?></td>
                <td class="p-4 text-right space-x-3">
                    <a href="add_question.php?quiz_id=<?= $quiz['id'] ?>" class="text-indigo-600 font-bold hover:underline">Questions</a>
                    <a href="manage_quizzes.php?cat_id=<?= $cat_id ?>&del_quiz=<?= $quiz['id'] ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if(empty($quizzes)) echo "<p class='p-4 text-gray-500 text-center'>Aucun quiz trouvé.</p>"; ?>
</div>

<div class="mt-4">
    <a href="dashboard.php" class="text-gray-500 underline">Retour aux catégories</a>
</div>

<?php require '../includes/footer.php'; ?>