<?php

namespace App\Providers;

use App\Blocks\BlockManager;
use Roots\Acorn\Sage\SageServiceProvider;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Initialize Block Manager for conditional asset loading
        // Only initialize if not in admin to avoid conflicts
        if (!is_admin()) {
            try {
                $blockManager = new BlockManager();
                $blockManager->init();
            } catch (\Exception $e) {
                // Silently fail if BlockManager has issues
            }
        }

        // Auto-register ACF blocks
        $this->registerBlocks();
    }

    /**
     * Auto-register ACF blocks from app/Blocks directory
     *
     * @return void
     */
    protected function registerBlocks()
    {
        // Only register blocks if ACF Composer is available
        if (!class_exists('Log1x\\AcfComposer\\Block')) {
            return;
        }

        $blocksPath = get_template_directory() . '/app/Blocks';

        if (!is_dir($blocksPath)) {
            return;
        }

        $files = glob($blocksPath . '/*.php');

        if (!$files || !is_array($files)) {
            return;
        }

        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }

            $className = 'App\\Blocks\\' . basename($file, '.php');

            // Skip manager class and example block
            $basename = basename($file, '.php');
            if (in_array($basename, ['BlockManager', 'ExampleBlock'])) {
                continue;
            }

            try {
                if (class_exists($className)) {
                    new $className();
                }
            } catch (\Exception $e) {
                // Silently skip blocks that fail to register
                continue;
            } catch (\Error $e) {
                // Also catch fatal errors
                continue;
            }
        }
    }
}
