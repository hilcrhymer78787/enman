import colors from 'vuetify/es5/util/colors'

export default {
    // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
    ssr: false,

    // Global page headers: https://go.nuxtjs.dev/config-head
    head: {
        titleTemplate: '%s - nuxt',
        title: 'enman',
        meta: [
            { charset: 'utf-8' },
            { name: 'viewport', content: 'width=device-width, initial-scale=1' },
            { hid: 'description', name: 'description', content: '' },
            { name: 'format-detection', content: 'telephone=no' },
            { name: 'apple-mobile-web-app-capable', content: 'yes' },
        ],
        link: [
            { href: '/launch.png', rel: 'apple-touch-startup-image' },
            { href: '/favicon.ico', rel: 'icon', type: 'image/x-icon' },
            { href: "splashscreens/iphone5_splash.png", media: "(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)", rel: "apple-touch-startup-image" },
            { href: "splashscreens/iphone6_splash.png", media: "(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)", rel: "apple-touch-startup-image" },
            { href: "splashscreens/iphoneplus_splash.png", media: "(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)", rel: "apple-touch-startup-image" },
            { href: "splashscreens/iphonex_splash.png", media: "(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)", rel: "apple-touch-startup-image" },
            { href: "splashscreens/iphonexr_splash.png", media: "(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)", rel: "apple-touch-startup-image" },
            { href: "splashscreens/iphonexsmax_splash.png", media: "(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)", rel: "apple-touch-startup-image" },
            { href: "splashscreens/ipad_splash.png", media: "(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)", rel: "apple-touch-startup-image" },
            { href: "splashscreens/ipadpro1_splash.png", media: "(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)", rel: "apple-touch-startup-image" },
            { href: "splashscreens/ipadpro3_splash.png", media: "(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)", rel: "apple-touch-startup-image" },
            { href: "splashscreens/ipadpro2_splash.png", media: "(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)", rel: "apple-touch-startup-image" },
        ],
    },
    server: {
        host: '0.0.0.0'
    },
    // Global CSS: https://go.nuxtjs.dev/config-css
    css: [
        '@/assets/common.scss',
    ],

    // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
    plugins: [
        { src: '~/plugins/constants.js' },
    ],

    // Auto import components: https://go.nuxtjs.dev/config-components
    components: true,

    // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
    buildModules: [
        // https://go.nuxtjs.dev/typescript
        '@nuxt/typescript-build',
        // https://go.nuxtjs.dev/vuetify
        '@nuxtjs/vuetify',
        '@nuxtjs/moment',
        'cookie-universal-nuxt',
    ],

    // Modules: https://go.nuxtjs.dev/config-modules
    modules: [
        // https://go.nuxtjs.dev/axios
        '@nuxtjs/axios',
        // https://go.nuxtjs.dev/pwa
        '@nuxtjs/pwa',
    ],

    // Axios module configuration: https://go.nuxtjs.dev/config-axios
    axios: {
        // baseURL: 'http://192.168.3.5:8000'
        // baseURL: 'http://localhost:8000'
        baseURL: 'https://enman-back.magicgifted.com/enman/laravel/public'
    },

    // PWA module configuration: https://go.nuxtjs.dev/pwa
    pwa: {
        meta: {
            theme_color: 'white',
            appleStatusBarStyle: 'default',
        },
        manifest: {
            name: 'enman',
            lang: 'ja',
            short_name: 'enman',
            title: 'enman',
            background_color: 'white',
        }
    },

    // Vuetify module configuration: https://go.nuxtjs.dev/config-vuetify
    vuetify: {
        customVariables: ['~/assets/variables.scss'],
        theme: {
            dark: false,
            themes: {
                dark: {
                    primary: colors.blue.darken2,
                    accent: colors.grey.darken3,
                    secondary: colors.amber.darken3,
                    info: colors.teal.lighten1,
                    warning: colors.amber.base,
                    error: colors.deepOrange.accent4,
                    success: colors.green.accent3
                }
            }
        }
    },

    // Build Configuration: https://go.nuxtjs.dev/config-build
    build: {
    }
}
