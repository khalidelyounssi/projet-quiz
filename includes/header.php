<?php
// تأكد أن السيشن بدات فكلشي الصفحات
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qodex Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="flex h-screen overflow-hidden">

    <aside class="hidden md:flex flex-col w-64 bg-slate-900 text-white shadow-2xl">
        <div class="p-6 flex items-center justify-center border-b border-slate-700">
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">QODEX</h1>
        </div>
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="../enseignant/dashboard.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition <?php echo basename($_SERVER['PHP_SELF'])=='dashboard.php'?'bg-blue-600':'' ?>">
                <i class="fas fa-chart-pie"></i> Statistiques
            </a>
            </nav>
        <div class="p-4 border-t border-slate-700">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold">
                    <?= substr($_SESSION['username'] ?? 'U', 0, 1) ?>
                </div>
                <div>
                    <p class="text-sm font-bold"><?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></p>
                    <p class="text-xs text-gray-400">Enseignant</p>
                </div>
            </div>
            <a href="../logout.php" class="block text-center w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition">Déconnexion</a>
        </div>
    </aside>

    <div id="mobile-menu" class="fixed inset-0 z-50 bg-slate-900 text-white transform -translate-x-full transition-transform duration-300 md:hidden flex flex-col">
        <div class="p-6 flex justify-between items-center border-b border-slate-700">
            <h1 class="text-2xl font-bold">QODEX</h1>
            <button id="close-menu" class="text-white text-2xl"><i class="fas fa-times"></i></button>
        </div>
        <nav class="flex-1 p-6 space-y-4">
            <a href="../enseignant/dashboard.php" class="block text-lg p-3 rounded hover:bg-slate-800"><i class="fas fa-chart-pie mr-2"></i> Tableau de bord</a>
            <a href="../logout.php" class="block text-lg p-3 rounded text-red-400 hover:bg-slate-800"><i class="fas fa-sign-out-alt mr-2"></i> Déconnexion</a>
        </nav>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="md:hidden bg-white shadow p-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-slate-900">QODEX</h1>
            <button id="open-menu" class="text-slate-900 text-2xl"><i class="fas fa-bars"></i></button>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">