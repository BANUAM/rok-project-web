<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TrialController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PUBLIC ROUTES (No Login Required)
// ==========================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/teams', [PageController::class, 'teams'])->name('teams');
Route::get('/achievements', [PageController::class, 'achievements'])->name('achievements');
Route::get('/learning-development', [PageController::class, 'learningDevelopment'])->name('learning-development');
Route::get('/bracket', [PageController::class, 'bracket'])->name('bracket');

// Trial Workflow
Route::get('/trial', [TrialController::class, 'index'])->name('trial');
Route::post('/trial/register', [TrialController::class, 'register'])->name('trial.register');
Route::get('/trial/c/{token}', [TrialController::class, 'showProfile'])->name('trial.profile');
Route::post('/trial/c/{token}/save', [TrialController::class, 'saveProfile'])->name('trial.save-profile');
Route::get('/trial/c/{token}/lsm', [TrialController::class, 'showLsm'])->name('trial.lsm');
Route::post('/trial/c/{token}/lsm/submit', [TrialController::class, 'submitLsm'])->name('trial.submit-lsm');
Route::get('/trial/c/{token}/status', [TrialController::class, 'showStatus'])->name('trial.status');

// ==========================================
// INTERNAL MANAGEMENT (Admin / Staff)
// ==========================================

Route::get('/management/login', [ManagementController::class, 'login'])->name('management.login');
Route::post('/management/login', [ManagementController::class, 'authenticate'])->name('management.authenticate');
Route::post('/management/logout', [ManagementController::class, 'logout'])->name('management.logout');

Route::prefix('management')->name('management.')->middleware('auth')->group(function () {
    Route::get('/', [ManagementController::class, 'dashboard'])->name('dashboard');

    // Hero Slides
    Route::get('/hero-slides', [ManagementController::class, 'heroSlides'])->name('hero-slides');
    Route::post('/hero-slides', [ManagementController::class, 'storeHeroSlide'])->name('hero-slides.store');
    Route::put('/hero-slides/{heroSlide}', [ManagementController::class, 'updateHeroSlide'])->name('hero-slides.update');
    Route::delete('/hero-slides/{heroSlide}', [ManagementController::class, 'deleteHeroSlide'])->name('hero-slides.delete');

    // Founders
    Route::get('/founders', [ManagementController::class, 'founders'])->name('founders');
    Route::post('/founders', [ManagementController::class, 'storeFounder'])->name('founders.store');
    Route::delete('/founders/{founder}', [ManagementController::class, 'deleteFounder'])->name('founders.delete');

    // Divisions
    Route::get('/divisions', [ManagementController::class, 'divisions'])->name('divisions');
    Route::post('/divisions', [ManagementController::class, 'storeDivision'])->name('divisions.store');
    Route::delete('/divisions/{division}', [ManagementController::class, 'deleteDivision'])->name('divisions.delete');

    // Players / Rosters
    Route::get('/players', [ManagementController::class, 'players'])->name('players');
    Route::post('/players', [ManagementController::class, 'storePlayer'])->name('players.store');
    Route::put('/players/{player}', [ManagementController::class, 'updatePlayer'])->name('players.update');
    Route::delete('/players/{player}', [ManagementController::class, 'deletePlayer'])->name('players.delete');

    // Achievements
    Route::get('/achievements', [ManagementController::class, 'achievements'])->name('achievements');
    Route::post('/achievements', [ManagementController::class, 'storeAchievement'])->name('achievements.store');
    Route::delete('/achievements/{achievement}', [ManagementController::class, 'deleteAchievement'])->name('achievements.delete');

    // Partners
    Route::get('/partners', [ManagementController::class, 'partners'])->name('partners');
    Route::post('/partners', [ManagementController::class, 'storePartner'])->name('partners.store');
    Route::delete('/partners/{partner}', [ManagementController::class, 'deletePartner'])->name('partners.delete');

    // Trial Candidates
    Route::get('/candidates', [ManagementController::class, 'candidates'])->name('candidates');
    Route::get('/candidates/{candidate}', [ManagementController::class, 'showCandidate'])->name('candidates.detail');
    Route::post('/candidates/{candidate}/status', [ManagementController::class, 'updateCandidateStatus'])->name('candidates.status');
    Route::post('/candidates/{candidate}/promote', [ManagementController::class, 'promoteCandidateToPlayer'])->name('candidates.promote');

    // LSM Management
    Route::get('/lsm', [ManagementController::class, 'lsm'])->name('lsm');
    Route::post('/lsm/{lsm}/settings', [ManagementController::class, 'updateLsmSettings'])->name('lsm.settings');
    Route::post('/lsm/{lsm}/questions', [ManagementController::class, 'storeQuestion'])->name('lsm.questions.store');
    Route::delete('/lsm/questions/{question}', [ManagementController::class, 'deleteQuestion'])->name('lsm.questions.delete');

    // Learning & Development
    Route::get('/learning', [ManagementController::class, 'learning'])->name('learning');
    Route::post('/learning/modules', [ManagementController::class, 'storeModule'])->name('learning.modules.store');
    Route::post('/learning/modules/{module}/lessons', [ManagementController::class, 'storeLesson'])->name('learning.lessons.store');

    // Tournaments
    Route::get('/tournaments', [ManagementController::class, 'tournaments'])->name('tournaments');
});
