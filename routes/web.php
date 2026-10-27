<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::post('/contact', [PortfolioController::class, 'contact'])->middleware('throttle:10,1')->name('contact.submit');
Route::get('/resume/download', [PortfolioController::class, 'downloadResume'])->name('resume.download');

// Live Chat System Endpoints
Route::prefix('api/chat')->group(function () {
    Route::get('/status', [ChatController::class, 'getStatus'])->name('chat.status');
    Route::post('/status', [ChatController::class, 'toggleStatus'])->name('chat.status.toggle');
    Route::get('/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/reply', [ChatController::class, 'adminReply'])->name('chat.reply');
    Route::get('/conversations', [ChatController::class, 'listConversations'])->name('chat.conversations');
});


