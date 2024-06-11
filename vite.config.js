import symfonyPlugin from "vite-plugin-symfony";
import { fileURLToPath } from 'node:url'
import vue from '@vitejs/plugin-vue'; // Importez le plugin Vue
import vueJsx from '@vitejs/plugin-vue-jsx'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { VueRouterAutoImports, getPascalCaseRouteName } from 'unplugin-vue-router'
import VueRouter from 'unplugin-vue-router/vite'
import { defineConfig } from 'vite'
import VueDevTools from 'vite-plugin-vue-devtools'
import Layouts from 'vite-plugin-vue-layouts'
import vuetify from 'vite-plugin-vuetify'
import svgLoader from 'vite-svg-loader'

export default defineConfig({
  plugins: [
    // Docs: https://github.com/posva/unplugin-vue-router
    // ℹ️ This plugin should be placed before vue plugin
    VueRouter({
      routesFolder: ['assets/admin/pages'],
      getRouteName: routeNode => {
        // Convert pascal case to kebab case
        return getPascalCaseRouteName(routeNode)
          .replace(/([a-z\d])([A-Z])/g, '$1-$2')
          .toLowerCase()
      },
    }),
    vue({
      template: {
        compilerOptions: {
          isCustomElement: tag => tag === 'swiper-container' || tag === 'swiper-slide',
        },
      },
    }),
    VueDevTools(),
    vueJsx(),

    // Docs: https://github.com/vuetifyjs/vuetify-loader/tree/master/packages/vite-plugin
    vuetify({
      styles: {
        configFile: 'assets/admin/assets/styles/variables/_vuetify.scss',
      },
    }),

    // Docs: https://github.com/johncampionjr/vite-plugin-vue-layouts#vite-plugin-vue-layouts
    Layouts({
      layoutsDirs: './assets/admin/layouts/',
    }),

    // Docs: https://github.com/antfu/unplugin-vue-components#unplugin-vue-components
    Components({
      dirs: ['assets/admin/@core/components', 'assets/admin/views/demos', 'assets/admin/components'],
      dts: true,
      resolvers: [
        componentName => {
          // Auto import `VueApexCharts`
          if (componentName === 'VueApexCharts')
            return { name: 'default', from: 'vue3-apexcharts', as: 'VueApexCharts' }
        },
      ],
    }),

    // Docs: https://github.com/antfu/unplugin-auto-import#unplugin-auto-import
    AutoImport({
      imports: ['vue', VueRouterAutoImports, '@vueuse/core', '@vueuse/math', 'vue-i18n', 'pinia'],
      dirs: [
        './assets/admin/@core/utils',
        './assets/admin/@core/composable/',
        './assets/admin/composables/',
        './assets/admin/utils/',
        './assets/admin/plugins/*/composables/*',
      ],
      vueTemplate: true,

      // ℹ️ Disabled to avoid confusion & accidental usage
      ignore: ['useCookies', 'useStorage'],
      eslintrc: {
        enabled: true,
        filepath: './.eslintrc-auto-import.json',
      },
    }),
    svgLoader(),
    symfonyPlugin(),
  ],

  define: { 'process.env': {} },

  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./assets/admin', import.meta.url)),
      '@themeConfig': fileURLToPath(new URL('./assets/admin/themeConfig.js', import.meta.url)),
      '@core': fileURLToPath(new URL('./assets/admin/@core', import.meta.url)),
      '@layouts': fileURLToPath(new URL('./assets/admin/@layouts', import.meta.url)),
      '@images': fileURLToPath(new URL('./assets/admin/assets/images/', import.meta.url)),
      '@styles': fileURLToPath(new URL('./assets/admin/assets/styles/', import.meta.url)),
      '@configured-variables': fileURLToPath(new URL('./assets/admin/assets/styles/variables/_template.scss', import.meta.url)),
      '@db': fileURLToPath(new URL('./assets/admin/plugins/fake-api/handlers/', import.meta.url)),
      '@api-utils': fileURLToPath(new URL('./assets/admin/plugins/fake-api/utils/', import.meta.url)),
    },
  },

  build: {
    chunkSizeWarningLimit: 5000,
    rollupOptions: {
      input: {
        app: "./assets/app.js"
      },
    }
  },
  optimizeDeps: {
    exclude: ['vuetify'],
    entries: [
      './assets/admin/**/*.vue',
    ],
  },
});
