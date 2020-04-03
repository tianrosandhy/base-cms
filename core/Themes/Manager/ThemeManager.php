<?php

namespace Core\Themes\Manager;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use File;

use Wiratama\Appearances\Theme\Exceptions\ThemeNotFoundException;
use Wiratama\Appearances\Theme\Json;
use Wiratama\Appearances\Theme\Theme;

use Core\Main\Models\SettingStructure;

class ThemeManager implements \Countable
{
    private $app;
    private $path;
    private $finder;

    public function __construct(Application $app, $path)
    {
        $this->app = $app;
        $this->path = $path;
        $this->finder = new Filesystem;
    }

    public function find($themeName)
    {
        foreach ($this->getDirectories() as $directory) {
            $resourceThemeDirectory = str_replace(realpath(base_path()), '', $directory);
            if (! str_contains(strtolower($resourceThemeDirectory), strtolower($themeName))) {
                continue;
            }

            return $this->getThemeInfoForPath(realpath($directory));
        }

        throw new ThemeNotFoundException($themeName);
    }

    public function all()
    {
        $themes = [];
        if (!$this->getFinder()->isDirectory($this->path)) {
            return $themes;
        }

        $directories = $this->getDirectories();

        foreach ($directories as $theme) {
            if (Str::startsWith($name = basename($theme), '.')) {
                continue;
            }
            $themes[$name] = $this->getThemeInfoForPath(realpath($theme));
        }

        return $themes;
    }

    public function allPublicThemes()
    {
        $themes = [];
        if (!$this->getFinder()->isDirectory($this->path)) {
            return $themes;
        }

        $directories = $this->getDirectories();
        foreach ($directories as $theme) {
            if (Str::startsWith($name = basename($theme), '.')) {
                continue;
            }
            $themeJson = $this->getThemeJsonFile($theme);
            if ($this->isFrontendTheme($themeJson)) {
                $themes[$name] = $this->getThemeInfoForPath(realpath($theme));
            }
        }

        return $themes;
    }

    public function allPublicThemesJson()
    {
        $themes = [];
        if (!$this->getFinder()->isDirectory($this->path)) {
            return $themes;
        }

        $directories = $this->getDirectories();

        foreach ($directories as $theme) {
            if (Str::startsWith($name = basename($theme), '.')) {
                continue;
            }
            $themeJson = $this->getThemeJsonFile($theme);
            if ($this->isFrontendTheme($themeJson)) {
                $themes[$name] = $this->getThemeInfoForPath(realpath($theme));
            }
        }

        return $themes;
    }

    private function getThemeInfoForPath($directory)
    {
        $themeJson = new Json($directory);

        $theme = new Theme(
            $themeJson->getJsonAttribute('name'),
            $themeJson->getJsonAttribute('description'),
            $directory,
            $themeJson->getJsonAttribute('parent')
        );
        $theme->tname = $themeJson->getJsonAttribute('name');
        $theme->tbasename = basename($directory);
        $theme->tdescription = $themeJson->getJsonAttribute('description');
        $theme->tdirectory = $directory;
        $theme->version = $themeJson->getJsonAttribute('version');
        $theme->thememenu = $themeJson->getJsonAttribute('thememenu');
        $theme->themeoption = $this->getThemeDataJsonAttribute($directory, 'themeoption');
        $theme->type = ucfirst($themeJson->getJsonAttribute('type'));
        $theme->active = $this->getStatus($theme); // false;
        return $theme;
    }

    private function getDirectories()
    {
        return $this->getFinder()->directories($this->path);
    }

    protected function getFinder()
    {
        return $this->app['files'];
    }

    protected function getConfig()
    {
        return $this->app['config'];
    }

    public function count()
    {
        return count($this->all());
    }

    public function getThemeJsonFile($theme)
    {
        return json_decode($this->getFinder()->get("$theme/theme.json"));
    }

    public function getThemeDataJsonAttribute($theme, $attribute)
    {
        $themeJsonPath = $theme.'/themedata.json';
        if (File::exists($themeJsonPath)) {
            $json = json_decode(File::get($themeJsonPath));

            if (isset($json->$attribute)) {
                return $json->$attribute;
            }
        }
        return false;
    }

    public function isFrontendTheme($themeJson)
    {
        return isset($themeJson->type) && $themeJson->type !== 'frontend' ? false : true;
    }

    public function getStatus(Theme $theme)
    {
        if ($theme->type !== 'Backend') {
            $active_theme = setting('site.frontend_theme');
            return $active_theme == $theme->getName();
        }
    }
}