<?php
session_start();
require '../config/database.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant') { header("Location: ../auth/login.php"); exit; }

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) { header("Location: dashboard.php"); exit; }

// جلب معلومات الكويز للرجوع
$stmtQ = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmtQ->execute([$quiz_id]);
$quiz = $stmtQ->fetch();

// إضافة سؤال
if (isset($_POST['add_q'])) {
    $sql = "INSERT INTO questions (id_quiz, question_text, correct_option, option1, option2, option3) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$quiz_id, $_POST['question'], $_POST['correct'], $_POST['opt1'], $_POST['opt2'], $_POST['opt3']]);
    header("Location: add_question.php?quiz_id=$quiz_id"); exit;
}

// حذف سؤال
if (isset($_GET['del_q'])) {
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$_GET['del_q']]);
    header("Location: add_question.php?quiz_id=$quiz_id"); exit;
}

// جلب الأسئلة
$stmtQs = $pdo->prepare("SELECT * FROM questions WHERE id_quiz = ? ORDER BY id DESC");
$stmtQs->execute([$quiz_id]);
$questions = $stmtQs->fetchAll();

require '../includes/header.php';
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold">Questions du Quiz: <span class="text-indigo-600"><?= htmlspecialchars($quiz['title']) ?></span></h1>
    <a href="manage_quizzes.php?cat_id=<?= $quiz['id_category'] ?>" class="text-gray-500 font-bold"><i class="fas fa-arrow-left"></i> Retour</a>
</div>

<div class="bg-white p-6 rounded shadow mb-8 border-t-4 border-green-500">
    <h2 class="font-bold mb-4">Ajouter une question</h2>
    <form method="POST">
        <textarea name="question" placeholder="La question..." required class="w-full border p-2 mb-4 rounded"></textarea>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <input type="text" name="correct" placeholder="✅ Bonne réponse" required class="border border-green-300 p-2 rounded">
            <input type="text" name="opt1" placeholder="❌ Mauvaise réponse 1" required class="border border-red-300 p-2 rounded">
            <input type="text" name="opt2" placeholder="❌ Mauvaise réponse 2" required class="border border-red-300 p-2 rounded">
            <input type="text" name="opt3" placeholder="❌ Mauvaise réponse 3" required class="border border-red-300 p-2 rounded">
        </div>
        <button type="submit" name="add_q" class="w-full bg-green-600 text-white py-2 rounded font-bold">Enregistrer</button>
    </form>
</div>

<div class="space-y-4">
    <?php foreach($questions as $q): ?>
        <div class="bg-white p-4 rounded shadow border flex justify-between items-start">
            <div>
                <h4 class="font-bold text-lg"><?= htmlspecialchars($q['question_text']) ?></h4>
                <div class="text-sm text-gray-600 mt-2">
                    <span class="text-green-600 font-bold mr-3">✔ <?= htmlspecialchars($q['correct_option']) ?></span>
                    <span class="mr-2">✘ <?= htmlspecialchars($q['option1']) ?></span>
                    <span class="mr-2">✘ <?= htmlspecialchars($q['option2']) ?></span>
                    <span>✘ <?= htmlspecialchars($q['option3']) ?></span>
                </div>
            </div>
            <a href="add_question.php?quiz_id=<?= $quiz_id ?>&del_q=<?= $q['id'] ?>" class="text-red-500" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
        </div>
    <?php endforeach; ?>
</div>

<?php require '../includes/footer.php'; ?>