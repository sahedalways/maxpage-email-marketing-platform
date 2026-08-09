<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\HasApiTokens;
use File;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public const BASE_DIR = 'app/users';
    public const ASSETS_DIR = 'home/files';
    public const ASSETS_THUMB_DIR = 'home/thumbs';
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'email_verified_at',
        'role',
        'timezone',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public static function guessUserTimezoneUsingAPI($ip)
    {

        $ip = Http::get('https://ipecho.net/' . $ip . '/json');
        if ($ip->json('timezone')) {
            return $ip->json('timezone');
        }
        return null;
    }
    public const TEMPLATES_DIR = 'home/templates';
    public function templates()
    {
        return $this->hasMany('App\Models\Template', 'customer_id');
    }
    public function getTemplatesPath($path = null)
    {
        $base = $this->getBasePath(self::TEMPLATES_DIR);

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }
    public function getBasePath($path = null)
    {
        $base = storage_path(join_paths(self::BASE_DIR, Auth::id()));

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getAssetsPath($path = null)
    {
        $base = $this->getBasePath(self::ASSETS_DIR);

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getThumbsPath($path = null)
    {
        $base = $this->getBasePath(self::ASSETS_THUMB_DIR);

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }
    public function createUserDirectories()
    {
        $paths = [
            $this->getBasePath(),
            $this->getAssetsPath(),
            $this->getThumbsPath(),
        ];

        foreach ($paths as $path) {
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
        }
    }
    public function getAssetsSubUrl()
    {
        $appSubDirectory = \App\Helpers\getAppSubdirectory();
        $appSubDirectory = (is_null($appSubDirectory)) ? '' : $appSubDirectory;

        // Returns a relative subpath like "/files/000000"
        $subpath = route('user_files', ['uid' => 0], false);
        return join_paths($appSubDirectory, $subpath);
    }

    public function getThumbsSubUrl()
    {
        $appSubDirectory = \App\Helpers\getAppSubdirectory();
        $appSubDirectory = (is_null($appSubDirectory)) ? '' : $appSubDirectory;

        // Returns a relative subpath like "/thumbs/000000"
        $subpath = route('user_thumbs', ['uid' => 0], false);
        return join_paths($appSubDirectory, $subpath);
    }

    public function getFilemanagerConfig()
    {
        // Create user directories if not exists
        $this->createUserDirectories();

        // Base URL with port, but without subpath
        $baseUrl = parse_url(url('/'));

        // port may be null
        if (!array_key_exists('port', $baseUrl)) {
            $baseUrl['port'] = '';
        }

        $baseUrl = "{$baseUrl['scheme']}://{$baseUrl['host']}{$baseUrl['port']}";

        // Limitation
        // Since this method is called from within the dialog.php file, there is no way to know if whether or not it is an admin or customer session
        // So, there is no chance to apply quota here
        // Virtually unlimited
        $maxSizeTotal = 2048;
        $maxSizeUpload = 2048;


        $config = [
            'base_url' => $baseUrl,

            // The `upload_dir` is needed to make the final file URL which is compatible with `user_files` route
            // For example: "/files/000000/example.jpg"
            'upload_dir' => join_paths('/', $this->getAssetsSubUrl(), '/'),
            'thumb_dir' => join_paths('/', $this->getThumbsSubUrl(), '/'),
            'thumbs_upload_dir' => join_paths('/', $this->getThumbsSubUrl(), '/'),
            // relative path from filemanager folder to upload folder, WITH FINAL /
            'current_path' => join_paths('../../storage/', self::BASE_DIR, '0', self::ASSETS_DIR, '/'),
            // relative path from filemanager folder to upload folder, WITH FINAL /
            'thumbs_base_path' => join_paths('../../storage/', self::BASE_DIR, '0', self::ASSETS_THUMB_DIR, '/'),
            'MaxSizeTotal' => $maxSizeTotal,
            'MaxSizeUpload' => $maxSizeUpload,
        ];

        return $config;
    }


    public function getBuilderTemplates()
    {
        $result = [];

        // Gallery
        $templates = \App\Models\Template::where('customer_id', '=', $this->id)
            ->orderBy('customer_id')
            ->get();

        foreach ($templates as $template) {
            $result[] = [
                'name' => $template->name,
                'url' => '', // action('CampaignController@templateChangeTemplate', ['uid' => $this->uid, 'template_uid' => $template->uid]),
                'thumbnail' => $template->getThumbUrl(),
            ];
        }

        return $result;
    }

    public static function copyTemplateAs(Template $template, $name)
    {
        $copy = $template->copy([
            'name' => $name,
            'customer_id' => Auth::id(),
        ]);

        return $copy;
    }
    public function languageDir()
    {
        return resource_path(join_paths('lang', $this->code));
    }
    public function getBuilderLang()
    {
        return include join_paths($this->languageDir(), 'builder.php');
    }

    public static function getAuthenticateFromFile()
    {
        $path = base_path('.authenticate');

        if (!isSiteDemo()) {
            return ['email' => '', 'password' => ''];
        }

        if (file_exists($path)) {
            $content = \File::get($path);
            $lines = explode("\n", $content);
            if (count($lines) > 1) {
                $demo = session()->get('demo');
                if (!isset($demo) || $demo == 'backend') {
                    return ['email' => $lines[0], 'password' => $lines[1]];
                } else {
                    return ['email' => $lines[2], 'password' => $lines[3]];
                }
            }
        }

        return ['email' => '', 'password' => ''];
    }

    protected static function booted()
    {
        parent::boot();
    }

    public function scopeFilterByRole($query)
    {
        return $query;
    }
}
