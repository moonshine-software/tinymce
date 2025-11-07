import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    emptyOutDir: false,
    rollupOptions: {
      input: ['resources/js/init.js', 'resources/css/tinymce.css'],
      output: {
        entryFileNames: `init.js`,
        assetFileNames: file => {
          let ext = file.name.split('.').pop()
          if (ext === 'css') {
            return 'tinymce.css'
          }

          return '[name].[ext]'
        }
      }
    },
    outDir: 'public',
  },
});
