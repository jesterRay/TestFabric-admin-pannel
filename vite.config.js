import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import html from '@rollup/plugin-html';
import { glob } from 'glob';

/**
 * Get Files from a directory
 * @param {string} query
 * @returns array 
 */
function GetFilesArray(query) {
  return glob.sync(query);
}

// Define critical files that need to load first
const criticalFiles = [
  'resources/assets/vendor/js/helpers.js',
  'resources/assets/js/config.js',
  'resources/assets/vendor/js/menu.js',
  'resources/assets/js/main.js'
];

/**
 * Js Files
 */
// Page JS Files (excluding files that are in criticalFiles)
const pageJsFiles = GetFilesArray('resources/assets/js/*.js')
  .filter(file => !criticalFiles.includes(file));

// Processing Vendor JS Files (excluding files that are in criticalFiles)
const vendorJsFiles = GetFilesArray('resources/assets/vendor/js/*.js')
  .filter(file => !criticalFiles.includes(file));

// Processing Libs JS Files
const LibsJsFiles = GetFilesArray('resources/assets/vendor/libs/**/*.js');

/**
 * Scss Files
 */
const CoreScssFiles = GetFilesArray('resources/assets/vendor/scss/**/!(_)*.scss');
const LibsScssFiles = GetFilesArray('resources/assets/vendor/libs/**/!(_)*.scss');
const LibsCssFiles = GetFilesArray('resources/assets/vendor/libs/**/*.css');
const FontsScssFiles = GetFilesArray('resources/assets/vendor/fonts/!(_)*.scss');

export default defineConfig({
  plugins: [
    laravel({
      input: [
        // Load critical files first
        ...criticalFiles,
        
        // CSS files
        'resources/css/app.css',
        'resources/assets/css/demo.css',
        
        // Core app.js
        'resources/js/app.js',
        
        // Other JS files
        ...pageJsFiles,
        ...vendorJsFiles,
        ...LibsJsFiles,
        
        // Style files
        ...CoreScssFiles,
        ...LibsScssFiles,
        ...LibsCssFiles,
        ...FontsScssFiles
      ],
      refresh: true
    }),
    html()
  ],
  build: {
    // Ensure proper chunk loading
    rollupOptions: {
      output: {
        manualChunks: {
          vendor: criticalFiles,
        }
      }
    }
  }
});