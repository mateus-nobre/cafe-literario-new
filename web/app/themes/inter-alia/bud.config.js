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

  const stylesBaseDir = join(__dirname, 'resources/styles');
  const scriptsBaseDir = join(__dirname, 'resources/scripts');
  const cssFiles = scanCssFiles(stylesBaseDir, stylesBaseDir);

  cssFiles.forEach(({ path, entryName }) => {
    const entries = [];

    // Convert path to Bud alias format: sections/header.css -> @styles/sections/header
    const styleAlias = `@styles/${path.replace('.css', '')}`;
    entries.push(styleAlias);

    // Check if corresponding JS file exists
    // sections/header.css -> sections/header.js
    const jsPath = path.replace('.css', '.js');
    const jsFullPath = join(scriptsBaseDir, jsPath);

    if (existsSync(jsFullPath)) {
      // Convert path to Bud alias format: sections/header.js -> @scripts/sections/header
      const scriptAlias = `@scripts/${jsPath.replace('.js', '')}`;
      entries.push(scriptAlias);
    }

    app.entry(entryName, entries);
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
  const proxyUrl = 'https://cafe-literario.lndo.site/';
  app
    .serve('http://0.0.0.0:3000')
    .setPublicUrl('http://localhost:3000')
    .setProxyUrl(proxyUrl)
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
      layout: {
        contentSize: '1200px',
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
