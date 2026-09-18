<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - ChezMoi
|--------------------------------------------------------------------------
*/

// Accueil & Recherche Avancée (cahier des charges)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentification & Session (Breeze)
require __DIR__.'/auth.php';

// Consultation de toutes les annonces (accessible à tous, y compris les acheteurs)
Route::get('/annonces', [AnnonceController::class, 'indexAll'])->name('annonces.index');

// Gestion des annonces : seules les personnes qui publient un bien y ont accès.
// L'administrateur modère les annonces depuis son espace dédié ci-dessous.
Route::middleware(['auth', 'role:proprietaire'])->group(function () {
    Route::get('/mes-annonces', [AnnonceController::class, 'index'])->name('annonces.mes-annonces');
    Route::get('/annonces/create', [AnnonceController::class, 'create'])->name('annonces.create');
    Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store');
    Route::get('/annonces/{annonce}/edit', [AnnonceController::class, 'edit'])->name('annonces.edit');
    Route::match(['put', 'patch'], '/annonces/{annonce}', [AnnonceController::class, 'update'])->name('annonces.update');
    Route::delete('/annonces/{annonce}', [AnnonceController::class, 'destroy'])->name('annonces.destroy');
});

// Consultation publique d'une annonce (après /annonces/create pour éviter les conflits de route)
Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');

// Espace Membre (Authentifié)
Route::middleware('auth')->group(function () {
    // Tableau de bord adapté au rôle
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Messagerie
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{user}', [MessageController::class, 'conversation'])->name('messages.show');
    Route::get('/annonces/{annonce}/contacter', [MessageController::class, 'contacter'])->name('messages.contacter');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::match(['post', 'patch'], '/notifications/tout-lu', [NotificationController::class, 'toutMarquerLu'])->name('notifications.tout-lu');
    Route::match(['post', 'patch'], '/notifications/{notification}/lu', [NotificationController::class, 'marquerLu'])->name('notifications.lu');
});

// Favoris (Acheteur uniquement)
Route::middleware(['auth', 'role:acheteur'])->group(function () {
    Route::get('/favoris', [FavoriController::class, 'index'])->name('favoris.index');
    Route::post('/favoris/{annonce}/toggle', [FavoriController::class, 'toggle'])->name('favoris.toggle');
    Route::delete('/favoris/{annonce}', [FavoriController::class, 'destroy'])->name('favoris.destroy');
});

// Espace Administration (Admin uniquement)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/annonces', [AdminController::class, 'annonces'])->name('annonces');
    Route::match(['post', 'patch'], '/annonces/{annonce}/valider', [AdminController::class, 'valider'])->name('annonces.valider');
    Route::match(['post', 'patch'], '/annonces/{annonce}/refuser', [AdminController::class, 'refuser'])->name('annonces.refuser');
    Route::delete('/annonces/{annonce}', [AdminController::class, 'destroyAnnonce'])->name('annonces.destroy');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::match(['post', 'patch'], '/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    Route::get('/type-biens', [AdminController::class, 'typeBiens'])->name('type-biens');
    Route::post('/type-biens', [AdminController::class, 'storeTypeBien'])->name('type-biens.store');
    Route::delete('/type-biens/{typeBien}', [AdminController::class, 'destroyTypeBien'])->name('type-biens.destroy');
});
