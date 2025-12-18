<?php
require '../config/database.php';
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant') { header("Location: ../auth/login.php"); exit; }

$sql = "SELECT r.*, u.username, q.title, q.id as quiz_id 
        FROM results r 
        JOIN users u ON r.id_etudiant = u.id 
        JOIN quizzes q ON r.id_quiz = q.id 
        JOIN categories c ON q.id_category = c.id 
        WHERE c.id_enseignant = ? 
        ORDER BY r.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$results = $stmt->fetchAll();

require '../includes/header.php';
?>

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Résultats des Étudiants</h1>
    <div class="bg-white px-4 py-2 rounded-lg shadow text-sm font-bold text-gray-500">
        Total: <?= count($results) ?> participation(s)
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-xs">
            <tr>
                <th class="p-4">Étudiant</th>
                <th class="p-4">Quiz</th>
                <th class="p-4">Score</th>
                <th class="p-4">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach($results as $res): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="p-4 font-bold text-gray-800 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">
                        <?= strtoupper(substr($res['username'], 0, 1)) ?>
                    </div>
                    <?= htmlspecialchars($res['username']) ?>
                </td>
                <td class="p-4 text-gray-600"><?= htmlspecialchars($res['title']) ?></td>
                <td class="p-4">
                    <?php 
                        $scoreColor = $res['score'] >= 50 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                    ?>
                    <span class="<?= $scoreColor ?> px-3 py-1 rounded-full text-xs font-bold">
                        <?= $res['score'] ?>%
                    </span>
                </td>
                <td class="p-4 text-gray-400 text-sm">
                    <?= date('d/m/Y H:i', strtotime($res['date_passage'] ?? 'now')) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php if(!$results): ?>
        <div class="p-12 text-center text-gray-400">
            <i class="fas fa-clipboard text-4xl mb-3 opacity-20"></i>
            <p>Aucun résultat disponible pour le moment.</p>
        </div>
    <?php endif; ?>
</div>

<?php require '../includes/footer.php'; ?>