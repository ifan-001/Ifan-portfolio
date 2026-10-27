<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ChatConsoleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\PushController;
use App\Http\Controllers\Admin\ResearchController;
use App\Http\Controllers\Admin\ResumeController as AdminResumeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Portfolio Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::post('/contact', [PortfolioController::class, 'contact'])->middleware('throttle:10,1')->name('contact.submit');
Route::get('/resume/download', [PortfolioController::class, 'downloadResume'])->name('resume.download');
Route::post('/track/click', [PortfolioController::class, 'trackClick'])->name('track.click');

// Public Blog
Route::get('/blog', [PortfolioController::class, 'blogIndex'])->name('blog.index');
Route::get('/blog/{slug}', [PortfolioController::class, 'blogShow'])->name('blog.show');

// PWA Assets
Route::get('/manifest.json', function () {
    return response()->json(json_decode(file_get_contents(public_path('manifest.json')), true));
});
Route::get('/sw.js', function () {
    return response(file_get_contents(public_path('sw.js')), 200, ['Content-Type' => 'application/javascript']);
});
Route::get('/offline.html', function () {
    return response(file_get_contents(public_path('offline.html')), 200, ['Content-Type' => 'text/html']);
});

/*
|--------------------------------------------------------------------------
| Public Live Chat System API
|--------------------------------------------------------------------------
*/
Route::prefix('api/chat')->group(function () {
    Route::get('/status', [ChatController::class, 'getStatus'])->name('chat.status');
    Route::post('/status', [ChatController::class, 'toggleStatus'])->name('chat.status.toggle');
    Route::get('/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/send', [ChatController::class, 'sendMessage'])->middleware('throttle:30,1')->name('chat.send');
    Route::post('/reply', [ChatController::class, 'adminReply'])->name('chat.reply');
    Route::get('/conversations', [ChatController::class, 'listConversations'])->name('chat.conversations');
});

/*
|--------------------------------------------------------------------------
| Discreet Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('portal')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| Protected Admin Dashboard & Management Routes (Desktop & Mobile PWA)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Overview Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/status/toggle', [DashboardController::class, 'toggleStatus'])->name('admin.dashboard.toggle-status');

    // Pillar 2: Content Management (Opens Public Portfolio in Visual CMS Mode)
    Route::get('/content', function () {
        return redirect('/?admin_mode=cms');
    })->name('admin.content');

    // Profile, Photo & Bio Management
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/profile/about-bio', [AdminProfileController::class, 'updateAboutBio'])->name('admin.profile.about-bio');
    Route::post('/profile/about-bio/restore', [AdminProfileController::class, 'restoreAboutBio'])->name('admin.profile.about-bio.restore');
    Route::post('/profile/photo', [AdminProfileController::class, 'updatePhoto'])->name('admin.profile.photo');
    Route::post('/profile/socials', [AdminProfileController::class, 'updateSocials'])->name('admin.profile.socials');

    // Resume Management
    Route::get('/resume', [AdminResumeController::class, 'index'])->name('admin.resume.index');
    Route::post('/resume', [AdminResumeController::class, 'update'])->name('admin.resume.update');

    // Skills Management
    Route::resource('skills', SkillController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'admin.skills.index',
        'store' => 'admin.skills.store',
        'update' => 'admin.skills.update',
        'destroy' => 'admin.skills.destroy',
    ]);

    // Projects Management
    Route::resource('projects', AdminProjectController::class)->names([
        'index' => 'admin.projects.index',
        'create' => 'admin.projects.create',
        'store' => 'admin.projects.store',
        'edit' => 'admin.projects.edit',
        'update' => 'admin.projects.update',
        'destroy' => 'admin.projects.destroy',
    ]);

    // Experience Management
    Route::resource('experience', ExperienceController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'admin.experience.index',
        'store' => 'admin.experience.store',
        'update' => 'admin.experience.update',
        'destroy' => 'admin.experience.destroy',
    ]);

    // Education Management
    Route::resource('education', EducationController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'admin.education.index',
        'store' => 'admin.education.store',
        'update' => 'admin.education.update',
        'destroy' => 'admin.education.destroy',
    ]);

    // Research & Highlights
    Route::get('/research', [ResearchController::class, 'index'])->name('admin.research.index');
    Route::post('/research/item', [ResearchController::class, 'storeItem'])->name('admin.research.item.store');
    Route::put('/research/item/{item}', [ResearchController::class, 'updateItem'])->name('admin.research.item.update');
    Route::delete('/research/item/{item}', [ResearchController::class, 'destroyItem'])->name('admin.research.item.destroy');
    Route::post('/research/achievement', [ResearchController::class, 'storeAchievement'])->name('admin.research.achievement.store');
    Route::delete('/research/achievement/{achievement}', [ResearchController::class, 'destroyAchievement'])->name('admin.research.achievement.destroy');

    // Blog Management
    Route::resource('blog', AdminBlogController::class)->names([
        'index' => 'admin.blog.index',
        'create' => 'admin.blog.create',
        'store' => 'admin.blog.store',
        'edit' => 'admin.blog.edit',
        'update' => 'admin.blog.update',
        'destroy' => 'admin.blog.destroy',
    ]);

    // Contact Inquiries
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('admin.inquiries.index');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('admin.inquiries.show');
    Route::post('/inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('admin.inquiries.status');
    Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('admin.inquiries.destroy');

    // Real-Time Chat Console
    Route::get('/chat', [ChatConsoleController::class, 'index'])->name('admin.chat.index');
    Route::post('/chat/reply', [ChatConsoleController::class, 'reply'])->name('admin.chat.reply');
    Route::get('/chat/poll/{conversation}', [ChatConsoleController::class, 'poll'])->name('admin.chat.poll');

    // Privacy-Conscious Telemetry & Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics.index');

    // Platform Settings & Security
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    Route::post('/settings/toggle-section', [SettingController::class, 'toggleSection'])->name('admin.settings.toggle-section');
    Route::post('/settings/password', [SettingController::class, 'updatePassword'])->name('admin.settings.password');

    // Web Push Notifications
    Route::post('/push/subscribe', [PushController::class, 'subscribe'])->name('admin.push.subscribe');
    Route::post('/push/unsubscribe', [PushController::class, 'unsubscribe'])->name('admin.push.unsubscribe');
    Route::post('/push/test', [PushController::class, 'sendTest'])->name('admin.push.test');
});
