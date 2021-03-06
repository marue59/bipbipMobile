var Encore = require('@symfony/webpack-encore');
Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')
    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')
    .addEntry('signup', './assets/scss/signup.scss')
    .addEntry('admin-tables', './assets/scss/admin-tables.scss')
    .addEntry('showEstimationAdmin', './assets/scss/showEstimationAdmin.scss')
    .addEntry('home', './assets/js/home.js')
    .addEntry('faq', './assets/js/faq.js')
    .addEntry('signature', './assets/js/signature.js')
    .addEntry('partnerList', './assets/scss/partnerList.scss')
    .addEntry('partnerPage', './assets/scss/partnerPage.scss')
    .addEntry('adminHomePage', './assets/js/adminHomePage.js')
    .addEntry('newPartner', './assets/scss/newPartner.scss')
    .addEntry('editPartner', './assets/scss/editPartner.scss')
    .addEntry('bdc', './assets/scss/bdc.scss')
    .addEntry('login', './assets/scss/login.scss')
    .addEntry('final_estimation', './assets/scss/final_estimation.scss')
    .addEntry('show_collect', './assets/js/show_collect.js')
    .addEntry('modal_infos', './assets/js/modal_infos.js')
    .addEntry('bdcVueAdmin', './assets/scss/bdcVueAdmin.scss')
    .addEntry('admin', './assets/scss/admin.scss')
    .addEntry('upload', './assets/js/upload.js')
    .addEntry('matrice_upload', './assets/js/matrice_upload.js')
    .addEntry('flash_m', './assets/js/flash_m.js')
    .addEntry('boutique', './assets/scss/boutique.scss')
    .addEntry('histoire', './assets/scss/histoire.scss')
    .addEntry('bipers', './assets/scss/bipers.scss')
    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()
    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    // enables @babel/preset-env polyfills
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    // enables Sass/SCSS support
    .enableSassLoader()
// uncomment if you use TypeScript
//.enableTypeScriptLoader()
// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
//.enableIntegrityHashes()
// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()
// uncomment if you use API Platform admin (composer req api-admin)
//.enableReactPreset()
//.addEntry('admin', './assets/js/adminHomePage.js')
module.exports = Encore.getWebpackConfig();
