<?php
require '../config/database.php';
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }

if(isset($_GET['del'])){
    $stmt=$pdo->prepare("DELETE FROM quizzes WHERE id=?");
    $stmt->execute([$_GET['del']]);
    header("Location: all_quizzes.php"); exit;
}

$sql = "SELECT q.*, c.name as cat_name FROM quizzes q JOIN categories c ON q.id_category = c.id WHERE c.id_enseignant = ? ORDER BY q.id DESC";
$stm = $pdo->prepare($sql);
$stm->execute([$_SESSION['user_id']]);
$quizzes = $stm->fetchAll();

require '../includes/header.php';
?>

<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <h1 class="text-2xl font-bold">Tous les Quiz</h1>
    <a href="add_quiz.php" class="bg-indigo-600 text-white px-6 py-3 rounded-xl shadow-lg hover:bg-indigo-700 transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Nouveau Quiz
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-xs">
            <tr>
                <th class="p-4">Titre du Quiz</th>
                <th class="p-4">Catégorie</th>
                <th class="p-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach($quizzes as $q): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="p-4 font-bold text-gray-800"><?= htmlspecialchars($q['title']) ?></td>
                <td class="p-4"><span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold"><?= htmlspecialchars($q['cat_name']) ?></span></td>
                <td class="p-4 text-right flex justify-end gap-3">
                    <a href="add_question.php?quiz_id=<?= $q['id'] ?>" class="text-indigo-600 hover:bg-indigo-50 px-3 py-1 rounded-lg font-bold text-sm transition">Questions</a>
                    <a href="edit_quiz.php?id=<?= $q['id'] ?>" class="text-gray-400 hover:text-blue-500 p-1"><i class="fas fa-edit"></i></a>
                    <a href="all_quizzes.php?del=<?= $q['id'] ?>" onclick="return confirm('Supprimer ?')" class="text-gray-400 hover:text-red-500 p-1"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if(!$quizzes) echo '<div class="p-8 text-center text-gray-400">Aucun quiz trouvé. Créez-en un !</div>'; ?>
</div>
<?php require '../includes/footer.php'; ?>