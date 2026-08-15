<?php

namespace App\Http\Controllers;

use App\Library\File;
use App\Library\StringHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    public function clear()
    {
        Artisan::call('route:cache');
        Artisan::call('config:cache');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return 'Routes cache has been cleared';
    }

    public function autoLogin(Request $request)
    {
        Auth::login(\App\Models\User::first());

        return redirect($request->get('to', '/admin/messages/send'));
    }

    public function userFiles($uid, $name = null)
    {
        $path = storage_path('app/users/' . $uid . '/home/files/' . $name);

        if (\File::exists($path)) {
            $mime_type = File::getFileType($path);

            return response()->file($path, ['Content-Type' => $mime_type]);
        }

        abort(404);
    }

    public function userThumbs($uid, $name = null)
    {
        $path = storage_path('app/users/' . $uid . '/home/thumbs/' . $name);

        if (\File::exists($path)) {
            $mime_type = File::getFileType($path);

            return response()->file($path, ['Content-Type' => $mime_type]);
        }

        abort(404);
    }

    public function publicAssets($dirname, $basename)
    {
        $dirname = StringHelper::base64UrlDecode($dirname);
        $absPath = storage_path(join_paths($dirname, $basename));

        if (\File::exists($absPath)) {
            $mimetype = File::getFileType($absPath);

            return response()->file($absPath, [
                'Content-Type' => $mimetype,
                'Content-Length' => filesize($absPath),
            ]);
        }

        abort(404);
    }
}
