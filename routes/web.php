<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ChatConsoleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\FeedPostController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\PushController;
use App\Http\Controllers\Admin\ResearchController;
use App\Http\Controllers\Admin\ResumeController as AdminResumeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\InteractiveAppController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Portfolio Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::post('/contact', [PortfolioController::class, 'contact'])->middleware('throttle:10,1')->name('contact.submit');
Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => url('/'), 'priority' => '1.0'],
        ['loc' => url('/blog'), 'priority' => '0.8'],
        ['loc' => url('/feed'), 'priority' => '0.7'],
        ['loc' => url('/apps'), 'priority' => '0.6'],
    ];
    foreach (\App\Models\BlogPost::published()->get() as $post) {
        $urls[] = ['loc' => url('/blog/' . $post->slug), 'priority' => '0.7', 'lastmod' => optional($post->updated_at)->toDateString()];
    }
    $xml = view('sitemap', compact('urls'))->render();
    return response($xml, 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');
Route::get('/resume/download', [PortfolioController::class, 'downloadResume'])->name('resume.download');
Route::get('/resume/view', [PortfolioController::class, 'resumeView'])->name('resume.view');
Route::post('/track/click', [PortfolioController::class, 'trackClick'])->middleware('throttle:60,1')->name('track.click');

// Public Blog
Route::get('/blog', [PortfolioController::class, 'blogIndex'])->name('blog.index');
Route::get('/blog/{slug}', [PortfolioController::class, 'blogShow'])->name('blog.show');

// Public Feed (Dedicated updates view, accessible strictly via header button)
Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
Route::post('/feed/{post}/like', [FeedController::class, 'like'])->middleware('throttle:30,1')->name('feed.like');
Route::post('/feed/{post}/comment', [FeedController::class, 'storeComment'])->middleware('throttle:5,1')->name('feed.comment');

// Interactive Apps & Tools (Live Resume Builder, Playable Games, QR Generator)
Route::get('/apps', [InteractiveAppController::class, 'index'])->name('apps.index');
Route::get('/apps/resume-builder', [InteractiveAppController::class, 'resumeBuilder'])->name('apps.resume-builder');
Route::get('/apps/cyber-game', [InteractiveAppController::class, 'cyberGame'])->name('apps.cyber-game');
Route::get('/apps/qr-medical', [InteractiveAppController::class, 'qrMedical'])->name('apps.qr-medical');
Route::get('/apps/toolkit', [InteractiveAppController::class, 'toolkit'])->name('apps.toolkit');
Route::get('/apps/games/{game}', [InteractiveAppController::class, 'game'])->where('game', '[a-z0-9-]+')->name('apps.game');

// PWA Assets
Route::get('/manifest.json', function () {
    $manifest = json_decode(file_get_contents(public_path('manifest.json')), true);
    $appConfig = config('portfolio.app', []);
    if (!empty($appConfig['name'])) {
        $manifest['name'] = $appConfig['name'];
    }
    if (!empty($appConfig['short_name'])) {
        $manifest['short_name'] = $appConfig['short_name'];
    }
    $manifest['start_url'] = '/portal/login';
    return response()->json($manifest);
});
Route::get('/sw.js', function () {
    return response(file_get_contents(public_path('sw.js')), 200, ['Content-Type' => 'application/javascript', 'Cache-Control' => 'no-cache, max-age=0']);
});
Route::get('/offline.html', function () {
    return response(file_get_contents(public_path('offline.html')), 200, ['Content-Type' => 'text/html']);
});

/*
|--------------------------------------------------------------------------
| Public Live Chat System API
|--------------------------------------------------------------------------
*/
// Built-in portfolio AI assistant (no external AI service)
Route::post('/api/assistant', [AssistantController::class, 'ask'])->middleware('throttle:30,1')->name('assistant.ask');

Route::prefix('api/chat')->group(function () {
    Route::get('/status', [ChatController::class, 'getStatus'])->name('chat.status');
    Route::get('/messages', [ChatController::class, 'getMessages'])->middleware('throttle:60,1')->name('chat.messages');
    Route::post('/send', [ChatController::class, 'sendMessage'])->middleware('throttle:30,1')->name('chat.send');

    // Admin-only chat controls (require an authenticated administrator session)
    Route::middleware('auth')->group(function () {
        Route::post('/status', [ChatController::class, 'toggleStatus'])->name('chat.status.toggle');
        Route::post('/reply', [ChatController::class, 'adminReply'])->name('chat.reply');
        Route::get('/conversations', [ChatController::class, 'listConversations'])->name('chat.conversations');
    });
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

    // Posts & Content Feed Management (Facebook/LinkedIn style updates)
    Route::get('/feed', [FeedPostController::class, 'index'])->name('admin.feed.index');
    Route::post('/feed', [FeedPostController::class, 'store'])->name('admin.feed.store');
    Route::post('/feed/{post}', [FeedPostController::class, 'update'])->name('admin.feed.update');
    Route::post('/feed/{post}/pin', [FeedPostController::class, 'togglePin'])->name('admin.feed.pin');
    Route::delete('/feed/{post}', [FeedPostController::class, 'destroy'])->name('admin.feed.destroy');
    Route::delete('/feed/comments/{comment}', [FeedPostController::class, 'destroyComment'])->name('admin.feed.comments.destroy');

    // Unified Inbox (Consolidates Live & Random Visitor Chats + Contact Inquiries)
    Route::get('/inbox', [InboxController::class, 'index'])->name('admin.inbox.index');
    Route::post('/inbox/chat/reply', [InboxController::class, 'replyChat'])->name('admin.inbox.chat.reply');
    Route::delete('/inbox/chat/{conversation}', [InboxController::class, 'destroyChat'])->name('admin.inbox.chat.destroy');
    Route::post('/inbox/inquiry/{inquiry}/status', [InboxController::class, 'updateInquiryStatus'])->name('admin.inbox.inquiry.status');
    Route::delete('/inbox/inquiry/{inquiry}', [InboxController::class, 'destroyInquiry'])->name('admin.inbox.inquiry.destroy');
    Route::get('/notifications/check', [InboxController::class, 'notificationsCheck'])->name('admin.notifications.check');

    // Legacy Aliases for backwards compatibility with existing links and tests
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('admin.inquiries.index');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('admin.inquiries.show');
    Route::post('/inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('admin.inquiries.status');
    Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('admin.inquiries.destroy');
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
