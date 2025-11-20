import { readdirSync, existsSync, statSync } from 'fs';
import { join } from 'path';
import { fileURLToPath } from 'url';
import { dirname } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

/**
 * Get all block names from app/Blocks directory
 * @returns {string[]}
 */
function getBlockNames() {
  const blocksPath = join(__dirname, 'app/Blocks');

  try {
    const files = readdirSync(blocksPath);

    return files
      .filter(file => file.endsWith('.php'))
      .map(file => file.replace('.php', ''))
      .filter(name => !['Block', 'BlockManager'].includes(name))
      .map(name => {
        // Convert PascalCase to kebab-case
        return name
          .replace(/([A-Z])/g, '-$1')
          .toLowerCase()
          .replace(/^-/, '');
      });
  } catch (e) {
    return [];
  }
}

/**
 * Recursively scan directory for CSS files
 * @param {string} dir - Directory to scan
 * @param {string} baseDir - Base directory for relative paths
 * @param {string[]} excludeFiles - Files to exclude
 * @returns {Array<{path: string, entryName: string}>}
 */
function scanCssFiles(dir, baseDir, excludeFiles = ['app.css', 'editor.css']) {
  const cssFiles = [];

  try {
    const entries = readdirSync(dir);

    for (const entry of entries) {
      const fullPath = join(dir, entry);
      const stat = statSync(fullPath);

      if (stat.isDirectory()) {
        // Recursively scan subdirectories
        cssFiles.push(...scanCssFiles(fullPath, baseDir, excludeFiles));
      } else if (entry.endsWith('.css') && !excludeFiles.includes(entry)) {
        // Get relative path from baseDir
        const relativePath = fullPath
          .replace(baseDir, '')
          .replace(/^[\/\\]+/, '')
          .replace(/\\/g, '/');
        // Convert path to entry name: sections/header.css -> section-header
        const entryName = relativePath
          .replace('.css', '')
          .split('/')
          .join('-');

        cssFiles.push({
          path: relativePath,
          entryName: entryName,
          fullPath: fullPath
        });
      }
    }
  } catch (e) {
    // Directory doesn't exist or can't be read
  }

  return cssFiles;
}

/**
 * Compiler configuration
 *
 * @see {@link https://roots.io/sage/docs sage documentation}
 * @see {@link https://bud.js.org/learn/config bud.js configuration guide}
 *
 * @type {import('@roots/bud').Config}
 */
export default async (app) => {
  /**
   * Application assets & entrypoints
   *
   * @see {@link https://bud.js.org/reference/bud.entry}
   * @see {@link https://bud.js.org/reference/bud.assets}
   */
  app
    .entry('app', ['@scripts/app', '@styles/app'])
    .entry('editor', ['@scripts/editor', '@styles/editor'])
    .assets(['images']);

  /**
   * Auto-register block-specific entrypoints
   * Each block gets its own CSS and JS bundle for conditional loading
   *
   * Blocks will be automatically discovered from app/Blocks directory.
   * For each block, create separate entrypoints if CSS/JS files exist:
   * - resources/styles/blocks/{block-name}.css
   * - resources/scripts/blocks/{block-name}.js
   *
   * Each block gets a unique entrypoint name: block-{block-name}
   * This allows conditional loading based on block usage.
   */
  const blockNames = getBlockNames();

  blockNames.forEach(blockName => {
    const cssPath = join(__dirname, `resources/styles/blocks/${blockName}.css`);
    const jsPath = join(__dirname, `resources/scripts/blocks/${blockName}.js`);
    const entryName = `block-${blockName}`;
    const entries = [];

    // Add CSS if file exists
    if (existsSync(cssPath)) {
      entries.push(`@styles/blocks/${blockName}`);
    }

    // Add JS if file exists
    if (existsSync(jsPath)) {
      entries.push(`@scripts/blocks/${blockName}`);
    }

    // Register entrypoint if at least one asset exists
    if (entries.length > 0) {
      app.entry(entryName, entries);
    }
  });

  /**
   * Auto-register CSS files from subdirectories based on folder hierarchy
   *
   * Scans resources/styles recursively and creates entrypoints for each CSS file
   * found in subdirectories. Entrypoint names are based on the folder structure:
   * - resources/styles/sections/header.css -> section-header
   * - resources/styles/components/button.css -> component-button
   *
   * These entrypoints can be enqueued automatically in PHP based on the hierarchy.
   */
  const stylesBaseDir = join(__dirname, 'resources/styles');
  const cssFiles = scanCssFiles(stylesBaseDir, stylesBaseDir);

  cssFiles.forEach(({ path, entryName }) => {
    // Convert path to Bud alias format: sections/header.css -> @styles/sections/header
    const styleAlias = `@styles/${path.replace('.css', '')}`;
    app.entry(entryName, [styleAlias]);
  });

  /**
   * Set public path
   *
   * @see {@link https://bud.js.org/reference/bud.setPublicPath}
   */
  app.setPublicPath('/app/themes/inter-alia/public/');

  /**
   * Development server settings
   *
   * @see {@link https://bud.js.org/reference/bud.serve}
   * @see {@link https://bud.js.org/reference/bud.setProxyUrl}
   * @see {@link https://bud.js.org/reference/bud.watch}
   */
  // Server listens on 0.0.0.0 to be accessible from outside container
  // But we set the public URL to localhost for browser access
  app
    .serve('http://0.0.0.0:3000')
    .setPublicUrl('http://localhost:3000')
    .setProxyUrl('https://cafe-literario.lndo.site/')
    .watch(['resources/views', 'app']);

  /**
   * Generate WordPress `theme.json`
   *
   * @note This overwrites `theme.json` on every build.
   *
   * @see {@link https://bud.js.org/extensions/sage/theme.json}
   * @see {@link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json}
   */
  app.wpjson
    .setSettings({
      background: {
        backgroundImage: true,
      },
      color: {
        custom: false,
        customDuotone: false,
        customGradient: false,
        defaultDuotone: false,
        defaultGradients: false,
        defaultPalette: false,
        duotone: [],
      },
      custom: {
        spacing: {},
        typography: {
          'font-size': {},
          'line-height': {},
        },
      },
      spacing: {
        padding: true,
        units: ['px', '%', 'em', 'rem', 'vw', 'vh'],
      },
      typography: {
        customFontSize: false,
      },
    })
    .useTailwindColors()
    .useTailwindFontFamily()
    .useTailwindFontSize();
};
