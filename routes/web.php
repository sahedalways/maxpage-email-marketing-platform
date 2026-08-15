<?php

use App\Http\Controllers\TemplateController;
use App\Http\Livewire\Admin\MessageHub\MessageHistory;
use App\Http\Livewire\Admin\MessageHub\MessageSettings;
use App\Http\Livewire\Admin\MessageHub\SendMessage;
use App\Http\Livewire\Admin\MessageHub\Templates;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageViewController;

Route::get('/clear', function () {
    Artisan::call('route:cache');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');

    return 'Routes cache has been cleared';
});

Route::get('/autologin', function () {
    Auth::login(\App\Models\User::first());
    return redirect(request('to', '/admin/messages/send'));
});

/* login */
Route::get('/', [\App\Http\Livewire\Login::class, '__invoke'])->name('login');

/* shared message hub + templates route definitions */
$messageHubRoutes = function () {
    Route::prefix('messages')->group(function () {
        Route::get('/send', SendMessage::class)->name('messages.send');
        Route::get('/hide/templates', Templates::class)->name('messages.templates');
        Route::get('/history', MessageHistory::class)->name('messages.history');
        Route::get('/gateway', MessageSettings::class)->name('messages.gateway');
    });
};

$templatesRoutes = function () {
    Route::prefix('templates')->group(function () {
        Route::get('/rss/parse', [TemplateController::class, 'parseRss'])->name('templates.parseRss');

        Route::post('/{uid}/export', [TemplateController::class, 'export'])->name('templates.export');
        Route::match(['get', 'post'], '/{uid}/change-name', [TemplateController::class, 'changeName'])->name('templates.changeName');
        Route::match(['get', 'post'], '/{uid}/categories', [TemplateController::class, 'categories'])->name('templates.categories');

        Route::match(['get', 'post'], '/{uid}/update-thumb-url', [TemplateController::class, 'updateThumbUrl'])->name('templates.updateThumbUrl');
        Route::match(['get', 'post'], '/{uid}/update-thumb', [TemplateController::class, 'updateThumb'])->name('templates.updateThumb');

        Route::get('/{uid}/builder/change-template/{change_uid}', [TemplateController::class, 'builderChangeTemplate'])->name('templates.builderChangeTemplate');
        Route::get('/builder/templates/{category_uid}', [TemplateController::class, 'builderTemplates'])->name('templates.builderTemplates');
        Route::match(['get', 'post'], '/builder/create', [TemplateController::class, 'builderCreate'])->name('templates.builderCreate');
        Route::post('/{uid}/builder/edit/asset', [TemplateController::class, 'uploadTemplateAssets'])->name('templates.uploadTemplateAssets');
        Route::get('/{uid}/builder/edit/content', [TemplateController::class, 'builderEditContent'])->name('templates.builderEditContent');
        Route::match(['get', 'post'], '/{uid}/builder/edit', [TemplateController::class, 'builderEdit'])->name('templates.builderEdit');

        Route::match(['get', 'post'], '/{uid}/copy', [TemplateController::class, 'copy'])->name('templates.copy');
        Route::get('/{uid}/preview', [TemplateController::class, 'preview'])->name('templates.preview');
        Route::get('/listing/{page?}', [TemplateController::class, 'listing'])->name('templates.listing');
        Route::match(['get', 'post'], '/upload', [TemplateController::class, 'uploadTemplate'])->name('templates.uploadTemplate');
        Route::get('/delete', [TemplateController::class, 'delete'])->name('templates.delete');
        Route::get('/{uid}/edit', [TemplateController::class, 'edit'])->name('templates.edit');
        Route::post('/{uid}/update', [TemplateController::class, 'update'])->name('template.update');
        Route::get('/', [TemplateController::class, 'index'])->name('templates.index');
    });
};

/* asset serving used by email templates */
Route::prefix('admin')->group(function () {
    Route::get('/files/{uid}/{name?}', [function ($uid, $name = null) {
        $path = storage_path('app/users/' . $uid . '/home/files/' . $name);
        if (\File::exists($path)) {
            $mime_type = \App\Library\File::getFileType($path);
            return response()->file($path, array('Content-Type' => $mime_type));
        } else {
            abort(404);
        }
    }])->where('name', '.+')->name('user_files');

    // assets path for customer thumbs
    Route::get('/thumbs/{uid}/{name?}', [function ($uid, $name = null) {
        $path = storage_path('app/users/' . $uid . '/home/thumbs/' . $name);
        if (\File::exists($path)) {
            $mime_type = \App\Library\File::getFileType($path);
            return response()->file($path, array('Content-Type' => $mime_type));
        } else {
            abort(404);
        }
    }])->where('name', '.+')->name('user_thumbs');

    // assets path for email (base64 encoded dirname)
    Route::get('assets/{dirname}/{basename}', [function ($dirname, $basename) {
        $dirname = \App\Library\StringHelper::base64UrlDecode($dirname);
        $absPath = storage_path(join_paths($dirname, $basename));

        if (\File::exists($absPath)) {
            $mimetype = \App\Library\File::getFileType($absPath);
            return response()->file($absPath, array(
                'Content-Type' => $mimetype,
                'Content-Length' => filesize($absPath),
            ));
        } else {
            abort(404);
        }
    }])->name('public_assets');
});

/* Reset Password */
Route::get('/reset-password/{token}', [\App\Http\Livewire\ResetPassword::class, '__invoke']);

/* admin section */
Route::group(['prefix' => 'admin', 'middleware' => ['store']], function () use ($messageHubRoutes, $templatesRoutes) {
    Route::get('/email/view/{id}', [MessageViewController::class, 'view'])->name('admin.email.view');

    /* Admin Dashboard */
    Route::get('dashboard', \App\Http\Livewire\Admin\Dashboard::class)->name('admin.dashboard')->middleware('admin');

    /* Admin Contacts */
    Route::get('contacts', \App\Http\Livewire\Admin\Contacts::class)->name('admin.contacts');

    $messageHubRoutes();
    $templatesRoutes();
});

Route::get('update', \App\Http\Livewire\Update\Updater::class)->name('update');
