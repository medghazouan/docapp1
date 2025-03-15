<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DemandeDocumentController;
use App\Http\Controllers\CertificatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
// User routes
    Route::resource('users', UserController::class);

    // Document routes
    Route::resource('documents', DocumentController::class);

    // Demande document routes
    Route::resource('demandes', DemandeDocumentController::class);
    Route::post('/demandes/{id}/approver-responsable', [DemandeDocumentController::class, 'approveByResponsable'])->name('demandes.approve.responsable');
    Route::post('/demandes/{id}/rejeter-responsable', [DemandeDocumentController::class, 'rejectByResponsable'])->name('demandes.reject.responsable');
    Route::post('/demandes/{id}/approver-archiviste', [DemandeDocumentController::class, 'approveByArchiviste'])->name('demandes.approve.archiviste');
    Route::post('/demandes/{id}/rejeter-archiviste', [DemandeDocumentController::class, 'rejectByArchiviste'])->name('demandes.reject.archiviste');
    Route::post('/demandes/{id}/recuperer', [DemandeDocumentController::class, 'markAsRetrieved'])->name('demandes.retrieve');

    // Certificat routes
    Route::get('/certificats/{id}', [CertificatController::class, 'show'])->name('certificats.show');
    Route::get('/certificats/{id}/download', [CertificatController::class, 'download'])->name('certificats.download');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read.all');
});
