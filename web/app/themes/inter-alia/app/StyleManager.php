<?php

/**
 * Style Manager
 *
 * Manages automatic CSS enqueue based on folder hierarchy
 */

namespace App;

use function Roots\bundle;

class StyleManager
{
    /**
     * Styles directory path
     *
     * @var string
     */
    protected $stylesDir;

    /**
     * Excluded files from auto-enqueue
     *
     * @var array
     */
    protected $excludedFiles = ['app.css', 'editor.css'];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stylesDir = get_template_directory() . '/resources/styles';
    }

    /**
     * Initialize style manager
     *
     * @return void
     */
    public function init()
    {
        // Enqueue styles based on folder hierarchy
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyles'], 20);
    }

    /**
     * Recursively scan directory for CSS files
     *
     * @param string $dir Directory to scan
     * @param string $baseDir Base directory for relative paths
     * @return array Array of CSS file info
     */
    protected function scanCssFiles($dir, $baseDir)
    {
        $cssFiles = [];

        if (!is_dir($dir)) {
            return $cssFiles;
        }

        try {
            $entries = scandir($dir);

            foreach ($entries as $entry) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }

                $fullPath = $dir . '/' . $entry;

                if (is_dir($fullPath)) {
                    // Recursively scan subdirectories
                    $cssFiles = array_merge($cssFiles, $this->scanCssFiles($fullPath, $baseDir));
                } elseif (pathinfo($entry, PATHINFO_EXTENSION) === 'css') {
                    // Check if file should be excluded
                    if (in_array($entry, $this->excludedFiles, true)) {
                        continue;
                    }

                    // Get relative path from baseDir
                    $relativePath = str_replace($baseDir . '/', '', $fullPath);
                    $relativePath = str_replace('\\', '/', $relativePath);

                    // Convert path to entry name: sections/header.css -> section-header
                    $entryName = str_replace('.css', '', $relativePath);
                    $entryName = str_replace('/', '-', $entryName);

                    $cssFiles[] = [
                        'path' => $relativePath,
                        'entryName' => $entryName,
                        'fullPath' => $fullPath,
                    ];
                }
            }
        } catch (\Exception $e) {
            // Directory doesn't exist or can't be read
        }

        return $cssFiles;
    }

    /**
     * Enqueue styles based on folder hierarchy
     *
     * @return void
     */
    public function enqueueStyles()
    {
        // Only run on frontend
        if (is_admin()) {
            return;
        }

        $cssFiles = $this->scanCssFiles($this->stylesDir, $this->stylesDir);

        foreach ($cssFiles as $cssFile) {
            $entryName = $cssFile['entryName'];

            try {
                $bundle = bundle($entryName);
                if ($bundle) {
                    $bundle->enqueue();
                }
            } catch (\Exception $e) {
                // Bundle doesn't exist, skip silently
                // This is normal if the file hasn't been compiled yet
            } catch (\Error $e) {
                // Also catch fatal errors
            } catch (\Throwable $e) {
                // Catch any other errors
            }
        }
    }
}

