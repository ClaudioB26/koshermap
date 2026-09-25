// Build CSS público (layouts/app). Tailwind v3 para igualar el CDN que se usaba antes.
// Regenerar: npx tailwindcss@3.4.17 -c tailwind.config.js -i resources/css/public.css -o public/css/app.css --minify
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './app/**/*.php',
    './lang/**/*.php',
  ],
  theme: { extend: {} },
  plugins: [],
};
