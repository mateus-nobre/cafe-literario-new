/**
 * Example Block Scripts
 *
 * This file contains JavaScript specific to the example-block.
 * These scripts will only be loaded when the block is used on the page.
 */

import domReady from '@roots/sage/client/dom-ready';

domReady(() => {
  // Example block initialization
  const exampleBlocks = document.querySelectorAll('.example-block');

  exampleBlocks.forEach(block => {
    // Initialize block functionality here
    console.log('Example block initialized', block);
  });
});

