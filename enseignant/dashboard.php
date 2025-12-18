<?php
require '../config/database.php';
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant') { header("Location: ../auth/login.php"); exit; }

$uid = $_SESSION['user_id'];
$nbCat = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE id_enseignant=?"); $nbCat->execute([$uid]); $nbCat=$nbCat->fetchColumn();
$nbQuiz = $pdo->prepare("SELECT COUNT(*) FROM quizzes q JOIN categories c ON q.id_category=c.id WHERE c.id_enseignant=?"); $nbQuiz->execute([$uid]); $nbQuiz=$nbQuiz->fetchColumn();
$nbQuest = $pdo->prepare("SELECT COUNT(*) FROM questions q JOIN quizzes z ON q.id_quiz=z.id JOIN categories c ON z.id_category=c.id WHERE c.id_enseignant=?"); $nbQuest->execute([$uid]); $nbQuest=$nbQuest->fetchColumn();

require '../includes/header.php';
?>
<h1 class="text-3xl font-bold mb-8 text-slate-800">Vue d'ensemble</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
            <p class="text-slate-500 font-medium">Catégories</p>
            <p class="text-4xl font-bold text-slate-800 mt-2"><?= $nbCat ?></p>
        </div>
        <div class="bg-blue-50 text-blue-600 p-4 rounded-xl text-2xl"><i class="fas fa-layer-group"></i></div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
            <p class="text-slate-500 font-medium">Quiz Créés</p>
            <p class="text-4xl font-bold text-slate-800 mt-2"><?= $nbQuiz ?></p>
        </div>
        <div class="bg-purple-50 text-purple-600 p-4 rounded-xl text-2xl"><i class="fas fa-file-alt"></i></div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
            <p class="text-slate-500 font-medium">Questions</p>
            <p class="text-4xl font-bold text-slate-800 mt-2"><?= $nbQuest ?></p>
        </div>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl text-2xl"><i class="fas fa-question"></i></div>
    </div>
</div>
<?php require '../includes/footer.php'; ?>