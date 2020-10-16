export default {
  /*
   ** Nuxt rendering mode
   ** See https://nuxtjs.org/api/configuration-mode
   */
  mode: 'spa',
  /*
   ** Nuxt target
   ** See https://nuxtjs.org/api/configuration-target
   */
  target: 'server',
  /*
   ** Headers of the page
   ** See https://nuxtjs.org/api/configuration-head
   */
  head: {
    title: 'BYTG DASHBOARD',
    meta: [{
        charset: 'utf-8'
      },
      {
        name: 'viewport',
        content: 'width=device-width, initial-scale=1'
      },
      {
        hid: 'description',
        name: 'description',
        content: process.env.npm_package_description || ''
      }
    ],
    link: [{
      rel: 'icon',
      type: 'image/x-icon',
      href: '/favicon.ico'
    }]
  },
  /*
   ** Global CSS
   */
  css: [
    '@/assets/sass/app.scss',
    'element-ui/lib/theme-chalk/index.css'
  ],
  /*
   ** Plugins to load before mounting the App
   ** https://nuxtjs.org/guide/plugins
   */
  plugins: [
    '@/plugins/element-ui'
  ],
  /*
   ** Auto import components
   ** See https://nuxtjs.org/api/configuration-components
   */
  components: true,
  /*
   ** Nuxt.js dev-modules
   */
  buildModules: [],
  /*
   ** Nuxt.js modules
   */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/auth'
  ],
  /*
   ** Axios module configuration
   ** See https://axios.nuxtjs.org/options
   */
  axios: {
    baseURL: process.env.API_URL || 'http://localhost:8000',
    credentials: true
  },
  /*
   ** Build configuration
   ** See https://nuxtjs.org/api/configuration-build/
   */
  auth: {
    redirect: {
      login: '/login',
      logout: '/',
      calback: '/login',
      home: '/'
    },
    strategies: {
      local: {
        endpoints: {
          login: {
            url: '/login',
            method: 'post',
            propertyName: false
          },
          logout: {
            url: '/logout',
            method: 'post'
          },
          user: {
            url: '/api/me',
            method: 'get',
            propertyName: false
          }
        },
        tokenType: false,
        tokenRequired: false
      }
    },
    localStorage: false
  },
  build: {
    transpile: [/^element-ui/],
  }
}
