<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;

class LanguageController extends Controller
{
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function index(Request $request)
    {
        $languages = ['en', 'ka']; // List of available languages

        // Get the selected language from the request or default to 'en'
        $defaultLang = $request->get('lang', 'en');

        // Load translations for the selected language
        $translations = $this->getTranslations($defaultLang);

        return view('admin.languages.index', compact('languages', 'translations', 'defaultLang'));
    }

    public function getTranslations($lang)
    {
        $path = resource_path("lang/{$lang}/messages.php");

        if (file_exists($path)) {
            return include $path;
        }

        return [];
    }

    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'lang' => 'required|string|in:en,ka',
            'value' => 'required|string',
        ]);

        $lang = $request->lang;
        $key = $request->key;
        $value = $request->value;

        $path = resource_path("lang/{$lang}/messages.php");

        if (!file_exists($path)) {
            $this->filesystem->put($path, "<?php\n\nreturn [];\n");
        }

        $translations = include $path;

        $translations[$key] = $value;

        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

        $this->filesystem->put($path, $content);

        return redirect()->back()->with('success', 'Translation saved successfully.');
    }
}
