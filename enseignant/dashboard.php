<?php
require '../config/database.php';
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant') { header("Location: ../auth/login.php"); exit; }

$user_id = $_SESSION['user_id'];

// --- LOGIC: Ajout / Suppression Catégorie ---
if (isset($_POST['add_cat'])) {
    $stmt = $pdo->prepare("INSERT INTO categories (name, id_enseignant) VALUES (?, ?)");
    $stmt->execute([$_POST['name'], $user_id]);
    header("Location: dashboard.php"); exit;
}
if (isset($_GET['del_cat'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$_GET['del_cat']]);
    header("Location: dashboard.php"); exit;
}

// --- STATISTIQUES ---
// 1. Nombre de Catégories
$stmtCatCount = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE id_enseignant = ?");
$stmtCatCount->execute([$user_id]);
$totalCat = $stmtCatCount->fetchColumn();

// 2. Nombre de Quiz (Jointure pour s'assurer que c'est les quiz de l'enseignant)
$stmtQuizCount = $pdo->prepare("SELECT COUNT(*) FROM quizzes q JOIN categories c ON q.id_category = c.id WHERE c.id_enseignant = ?");
$stmtQuizCount->execute([$user_id]);
$totalQuiz = $stmtQuizCount->fetchColumn();

// 3. Nombre de Questions
$stmtQCount = $pdo->prepare("SELECT COUNT(*) FROM questions q JOIN quizzes z ON q.id_quiz = z.id JOIN categories c ON z.id_category = c.id WHERE c.id_enseignant = ?");
$stmtQCount->execute([$user_id]);
$totalQuestions = $stmtQCount->fetchColumn();

// Get Categories for display
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id_enseignant = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$categories = $stmt->fetchAll();

require '../includes/header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-purple-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-200">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-indigo-100 text-sm font-medium uppercase">Catégories</p>
                <p class="text-4xl font-bold mt-2"><?= $totalCat ?></p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg"><i class="fas fa-layer-group text-2xl"></i></div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl p-6 text-white shadow-lg shadow-blue-200">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-blue-100 text-sm font-medium uppercase">Total Quiz</p>
                <p class="text-4xl font-bold mt-2"><?= $totalQuiz ?></p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg"><i class="fas fa-file-alt text-2xl"></i></div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl p-6 text-white shadow-lg shadow-teal-200">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-teal-100 text-sm font-medium uppercase">Questions</p>
                <p class="text-4xl font-bold mt-2"><?= $totalQuestions ?></p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg"><i class="fas fa-question text-2xl"></i></div>
        </div>
    </div>
</div>

<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <h2 class="text-2xl font-bold text-gray-800">Gestion des Catégories</h2>
    
    <form method="POST" class="flex w-full md:w-auto bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <input type="text" name="name" placeholder="Nouvelle matière..." required class="px-4 py-2 w-full md:w-64 outline-none text-sm">
        <button type="submit" name="add_cat" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 font-medium transition">
            <i class="fas fa-plus"></i>
        </button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($categories as $cat): ?>
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($cat['name']) ?></h3>
                    <div class="flex gap-2">
                        <a href="edit_category.php?id=<?= $cat['id'] ?>" class="text-gray-400 hover:text-blue-500"><i class="fas fa-pen"></i></a>
                        <a href="dashboard.php?del_cat=<?= $cat['id'] ?>" onclick="return confirm('Supprimer ?')" class="text-gray-400 hover:text-red-500"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
                <a href="manage_quizzes.php?cat_id=<?= $cat['id'] ?>" class="block w-full text-center bg-indigo-50 text-indigo-700 py-2 rounded-lg font-bold text-sm hover:bg-indigo-100 transition">
                    Voir les Quiz ➜
                </a>
            </div>
        </div>
    <?php endforeach; ?>
    
    <?php if(count($categories) == 0): ?>
        <div class="col-span-full text-center py-10 text-gray-400">
            <i class="fas fa-folder-open text-4xl mb-3 opacity-30"></i>
            <p>Aucune catégorie pour le moment.</p>
        </div>
    <?php endif; ?>
</div>

<?php require '../includes/footer.php'; ?>