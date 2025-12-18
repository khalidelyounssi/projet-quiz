<?php
require '../config/database.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant') { header("Location: ../auth/login.php"); exit; }

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: dashboard.php"); exit; }

// نجيبو معلومات الكويز + الكاتيكوري باش نعرفو فين نرجعو
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$id]);
$quiz = $stmt->fetch();
$cat_id = $quiz['id_category']; // مهم جداً للرجوع

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE quizzes SET title = ? WHERE id = ?");
    $stmt->execute([$_POST['title'], $id]);
    // نرجعو للصفحة د الكويزات د الكاتيكوري
    header("Location: manage_quizzes.php?cat_id=$cat_id"); exit;
}

require '../includes/header.php';
?>

<div class="max-w-xl mx-auto mt-10">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-blue-600 p-6 text-white">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-edit"></i> Modifier le Quiz
            </h2>
        </div>
        <div class="p-8">
            <form method="POST">
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Titre du Quiz</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($quiz['title']) ?>" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                
                <div class="flex gap-4">
                    <a href="manage_quizzes.php?cat_id=<?= $cat_id ?>" class="flex-1 text-center py-3 bg-gray-100 text-gray-700 rounded-lg font-bold hover:bg-gray-200 transition">Annuler</a>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 shadow-md transition">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>