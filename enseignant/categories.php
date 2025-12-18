<?php
require '../config/database.php';
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }

if(isset($_POST['add'])){
    $stmt=$pdo->prepare("INSERT INTO categories (name, id_enseignant) VALUES (?,?)");
    $stmt->execute([$_POST['name'], $_SESSION['user_id']]);
    header("Location: categories.php"); exit;
}
if(isset($_GET['del'])){
    $stmt=$pdo->prepare("DELETE FROM categories WHERE id=?");
    $stmt->execute([$_GET['del']]);
    header("Location: categories.php"); exit;
}

$cats = $pdo->prepare("SELECT * FROM categories WHERE id_enseignant=? ORDER BY id DESC");
$cats->execute([$_SESSION['user_id']]);
$cats = $cats->fetchAll();

require '../includes/header.php';
?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Gérer les Catégories</h1>
</div>

<form method="POST" class="bg-white p-4 rounded-xl shadow-sm mb-8 flex gap-3">
    <input type="text" name="name" required placeholder="Nom de la matière..." class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-4 outline-none focus:border-blue-500">
    <button type="submit" name="add" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-bold">Ajouter</button>
</form>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($cats as $c): ?>
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition group">
        <h3 class="font-bold text-lg mb-4"><?= htmlspecialchars($c['name']) ?></h3>
        <div class="flex justify-between items-center">
            <a href="edit_category.php?id=<?= $c['id'] ?>" class="text-gray-400 hover:text-blue-500"><i class="fas fa-edit"></i> Modifier</a>
            <a href="categories.php?del=<?= $c['id'] ?>" onclick="return confirm('Supprimer ?')" class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php require '../includes/footer.php'; ?>