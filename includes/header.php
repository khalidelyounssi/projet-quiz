<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isActive($page) {
    return basename($_SERVER['PHP_SELF']) == $page 
        ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' 
        : 'text-gray-400 hover:bg-slate-800 hover:text-white';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qodex - Espace Enseignant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c7c7c7; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a0a0a0; }
    </style>
</head>
<body class="bg-gray-100 text-slate-800">

<div class="flex h-screen overflow-hidden">
    
    <aside class="hidden md:flex flex-col w-64 bg-slate-900 text-white shadow-2xl z-20">
        <div class="h-20 flex items-center justify-center border-b border-slate-700">
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500 tracking-wider">
                QODEX
            </h1>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto">
            <p class="text-xs font-bold text-slate-500 uppercase px-2 mb-2">Principal</p>
            
            <a href="dashboard.php" class="flex items-center gap-3 p-3 rounded-xl transition-all duration-300 <?= isActive('dashboard.php') ?>">
                <i class="fas fa-chart-pie w-6"></i> 
                <span class="font-medium">Statistiques</span>
            </a>

            <a href="categories.php" class="flex items-center gap-3 p-3 rounded-xl transition-all duration-300 <?= isActive('categories.php') ?>">
                <i class="fas fa-layer-group w-6"></i> 
                <span class="font-medium">Catégories</span>
            </a>

            <a href="all_quizzes.php" class="flex items-center gap-3 p-3 rounded-xl transition-all duration-300 <?= isActive('all_quizzes.php') ?>">
                <i class="fas fa-file-alt w-6"></i> 
                <span class="font-medium">Tous les Quiz</span>
            </a>

            <a href="view_results.php" class="flex items-center gap-3 p-3 rounded-xl transition-all duration-300 <?= isActive('view_results.php') ?>">
                <i class="fas fa-graduation-cap w-6"></i> 
                <span class="font-medium">Résultats</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-700 bg-slate-900">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-sm font-bold">
                    <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold truncate"><?= htmlspecialchars($_SESSION['username'] ?? 'Utilisateur') ?></p>
                    <p class="text-xs text-gray-400">Enseignant</p>
                </div>
            </div>
            <a href="../logout.php" class="flex items-center justify-center gap-2 text-red-400 hover:text-white hover:bg-red-500/20 p-2 rounded-lg transition w-full text-sm font-bold border border-slate-700 hover:border-red-500/50">
                <i class="fas fa-power-off"></i> Déconnexion
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden relative">
        
        <header class="md:hidden bg-slate-900 text-white h-16 flex justify-between items-center px-4 shadow-md z-30">
            <span class="font-bold text-xl bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">QODEX</span>
            <button id="mobileBtn" class="text-2xl focus:outline-none p-2 rounded hover:bg-slate-800">
                <i class="fas fa-bars"></i>
            </button>
        </header>

        <div id="mobileMenu" class="fixed inset-0 bg-slate-900/95 z-40 transform translate-x-full transition-transform duration-300 md:hidden flex flex-col justify-center items-center space-y-8 text-white text-xl font-bold backdrop-blur-sm">
            <button id="closeBtn" class="absolute top-6 right-6 text-4xl text-gray-400 hover:text-white transition">&times;</button>
            
            <a href="dashboard.php" class="hover:text-blue-400 transition transform hover:scale-110">Statistiques</a>
            <a href="categories.php" class="hover:text-blue-400 transition transform hover:scale-110">Catégories</a>
            <a href="all_quizzes.php" class="hover:text-blue-400 transition transform hover:scale-110">Quiz</a>
            <a href="view_results.php" class="hover:text-blue-400 transition transform hover:scale-110">Résultats</a>
            
            <div class="w-16 h-1 bg-slate-700 rounded-full my-4"></div>
            
            <a href="../logout.php" class="text-red-500 hover:text-red-400 flex items-center gap-2">
                <i class="fas fa-power-off"></i> Déconnexion
            </a>
        </div>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 md:p-8">