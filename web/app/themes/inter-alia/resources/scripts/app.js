import domReady from '@roots/sage/client/dom-ready';

/**
 * Application entrypoint
 */
domReady(async () => {
  // Adicione seu JavaScript aqui
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
if (import.meta.webpackHot) import.meta.webpackHot.accept(console.error);
