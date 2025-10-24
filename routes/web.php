<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProjectController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\CareerController;
use App\Http\Controllers\PWAController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\PushNotificationController;

use App\Http\Controllers\NotificationPreferencesController;

// PWA Routes
Route::get('/manifest.json', [PWAController::class, 'manifest'])->name('pwa.manifest');
Route::get('/sw.js', [PWAController::class, 'serviceWorker'])->name('pwa.sw');
Route::get('/pwa-test', [PWAController::class, 'test'])->name('pwa.test');

// Sync API Routes
Route::prefix('api/sync')->name('sync.')->group(function () {
    Route::post('/process', [SyncController::class, 'processSync'])->name('process');
    Route::get('/status', [SyncController::class, 'getQueueStatus'])->name('status');
    Route::post('/clear', [SyncController::class, 'clearQueue'])->name('clear');
    Route::get('/token', [SyncController::class, 'refreshToken'])->name('token');
});

// Push Notification API Routes
Route::prefix('api/push')->name('push.')->group(function () {
    Route::get('/config', [PushNotificationController::class, 'getConfig'])->name('config');
    Route::post('/subscribe', [PushNotificationController::class, 'subscribe'])->name('subscribe');
    Route::post('/unsubscribe', [PushNotificationController::class, 'unsubscribe'])->name('unsubscribe');
    Route::post('/verify', [PushNotificationController::class, 'verify'])->name('verify');
    Route::post('/test', [PushNotificationController::class, 'sendTest'])->name('test');
    Route::post('/send-all', [PushNotificationController::class, 'sendToAll'])->name('send.all');
    Route::get('/stats', [PushNotificationController::class, 'getStats'])->name('stats');
    Route::post('/cleanup', [PushNotificationController::class, 'cleanup'])->name('cleanup');
});


// User Notification Preferences Routes
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/preferences', [NotificationPreferencesController::class, 'index'])->name('preferences');
    Route::get('/status', [NotificationPreferencesController::class, 'getStatus'])->name('status');
    Route::post('/remove', [NotificationPreferencesController::class, 'removeSubscription'])->name('remove');
    Route::post('/test', [NotificationPreferencesController::class, 'sendTest'])->name('test');
});

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Projects
Route::prefix('projects')->name('projects.')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProjectController::class, 'show'])->name('show');
});

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// Careers
Route::prefix('careers')->name('careers.')->group(function () {
    Route::get('/', [CareerController::class, 'index'])->name('index');
    Route::get('/{slug}', [CareerController::class, 'show'])->name('show');
    Route::post('/{slug}/apply', [CareerController::class, 'apply'])->name('apply');
});

// Contact
Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::post('/', [ContactController::class, 'store'])->name('store');
    Route::post('/project-inquiry', [ContactController::class, 'projectInquiry'])->name('project.inquiry');
});

// Static Pages (About, etc.)
Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
