<?php
require '../config/database.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant') { header("Location: ../auth/login.php"); exit; }

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: dashboard.php"); exit; }


$stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->execute([$id]);
$q = $stmt->fetch();
$quiz_id = $q['id_quiz']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "UPDATE questions SET question_text=?, correct_option=?, option1=?, option2=?, option3=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['question'], $_POST['correct'], $_POST['opt1'], $_POST['opt2'], $_POST['opt3'], $id]);
    header("Location: add_question.php?quiz_id=$quiz_id"); exit;
}

require '../includes/header.php';
?>

<div class="max-w-3xl mx-auto mt-6">
    <div class="bg-white rounded-2xl shadow-lg border-t-8 border-indigo-600">
        <div class="p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">Modification de la question</h2>
            
            <form method="POST">
                <div class="mb-8">
                    <label class="block text-gray-600 text-sm font-bold uppercase mb-2">Énoncé de la question</label>
                    <textarea name="question" required rows="3" class="w-full p-4 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-lg font-medium"><?= htmlspecialchars($q['question_text']) ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="relative">
                        <label class="block text-green-700 text-xs font-bold uppercase mb-1">✅ Bonne Réponse</label>
                        <input type="text" name="correct" value="<?= htmlspecialchars($q['correct_option']) ?>" required class="w-full p-3 bg-green-50 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-400 text-green-800 font-bold">
                    </div>
                    
                    <div>
                        <label class="block text-red-500 text-xs font-bold uppercase mb-1">❌ Choix incorrect 1</label>
                        <input type="text" name="opt1" value="<?= htmlspecialchars($q['option1']) ?>" required class="w-full p-3 bg-red-50 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-300 text-gray-700">
                    </div>
                    
                    <div>
                        <label class="block text-red-500 text-xs font-bold uppercase mb-1">❌ Choix incorrect 2</label>
                        <input type="text" name="opt2" value="<?= htmlspecialchars($q['option2']) ?>" required class="w-full p-3 bg-red-50 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-300 text-gray-700">
                    </div>
                    
                    <div>
                        <label class="block text-red-500 text-xs font-bold uppercase mb-1">❌ Choix incorrect 3</label>
                        <input type="text" name="opt3" value="<?= htmlspecialchars($q['option3']) ?>" required class="w-full p-3 bg-red-50 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-300 text-gray-700">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 border-t pt-6">
                    <a href="add_question.php?quiz_id=<?= $quiz_id ?>" class="text-gray-500 font-bold hover:text-gray-700 px-4 py-2">Annuler</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>