<?php
require '../config/database.php';
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }

if(!isset($_GET['id'])) { header("Location: dashboard.php"); exit; }
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->execute([$id]);
$q = $stmt->fetch();
$quiz_id = $q['id_quiz']; 

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $sql = "UPDATE questions SET question_text=?, correct_option=?, option1=?, option2=?, option3=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['q'], 
        $_POST['ok'], 
        $_POST['no1'], 
        $_POST['no2'], 
        $_POST['no3'], 
        $id
    ]);
    header("Location: add_question.php?quiz_id=$quiz_id");
    exit;
}

require '../includes/header.php';
?>

<div class="max-w-3xl mx-auto mt-6">
    <div class="bg-white rounded-2xl shadow-lg border-t-8 border-indigo-600 p-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Modifier la question</h2>
        
        <form method="POST">
            <div class="mb-6">
                <label class="block text-gray-500 text-sm font-bold uppercase mb-2">Question</label>
                <textarea name="q" required class="w-full border-2 border-gray-200 p-4 rounded-xl text-lg h-32 focus:border-indigo-500 focus:ring-0 transition"><?= htmlspecialchars($q['question_text']) ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-green-600 text-xs font-bold uppercase mb-1">✅ Bonne Réponse</label>
                    <input type="text" name="ok" value="<?= htmlspecialchars($q['correct_option']) ?>" class="w-full p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 font-bold">
                </div>
                <div>
                    <label class="block text-red-500 text-xs font-bold uppercase mb-1">❌ Fausse 1</label>
                    <input type="text" name="no1" value="<?= htmlspecialchars($q['option1']) ?>" class="w-full p-3 bg-red-50 border border-red-200 rounded-lg">
                </div>
                <div>
                    <label class="block text-red-500 text-xs font-bold uppercase mb-1">❌ Fausse 2</label>
                    <input type="text" name="no2" value="<?= htmlspecialchars($q['option2']) ?>" class="w-full p-3 bg-red-50 border border-red-200 rounded-lg">
                </div>
                <div>
                    <label class="block text-red-500 text-xs font-bold uppercase mb-1">❌ Fausse 3</label>
                    <input type="text" name="no3" value="<?= htmlspecialchars($q['option3']) ?>" class="w-full p-3 bg-red-50 border border-red-200 rounded-lg">
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t">
                <a href="add_question.php?quiz_id=<?= $quiz_id ?>" class="px-6 py-3 bg-gray-100 rounded-xl font-bold text-gray-600 hover:bg-gray-200 transition">Annuler</a>
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg transition">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>

<?php require '../includes/footer.php'; ?>