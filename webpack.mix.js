let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .styles(
        [
            'resources/assets/css/bootstrap.min.css',
            'resources/assets/css/font-awesome.css',
            'resources/assets/css/ionicons.css',
            'resources/assets/css/select2.css',			
            'resources/assets/admin-lte/css/AdminLTE.min.css',
            'resources/assets/admin-lte/css/skins/skin-purple.min.css',
            'resources/assets/css/jquery.dataTables.css',
            'resources/assets/css/admin.css'
        ],
        'public/css/admin.min.css'
    )
    .styles(
        [
            'resources/assets/css/bootstrap.css',
            'resources/assets/css/ds-font.css',
            'resources/assets/css/owl.carousel.css',
            'resources/assets/css/owl.theme.default.css',
            'resources/assets/css/styles.css',
			'resources/assets/css/responsive.css',
			'resources/assets/css/color.css',
        ],
        'public/css/style.min.css'
    )
    .scripts(
        [
            'resources/assets/js/jquery-2.2.3.min.js',
            'resources/assets/js/bootstrap.min.js',
            'resources/assets/js/select2.min.js',
            'resources/assets/js/jquery.dataTables.min.js',
            'resources/assets/admin-lte/js/app.js'
        ],
        'public/js/admin.min.js'
    )
    .scripts(
        [
            'resources/assets/js/popper.min.js',
			'resources/assets/js/bootstrap.js',
            'resources/assets/js/owl.carousel.js'
           // 'resources/assets/js/Drift.min.js'
        ],
        'public/js/front.min.js'
    )
    //.copyDirectory('node_modules/datatables/media/images', 'public/images')
    //.copyDirectory('node_modules/font-awesome/fonts', 'public/fonts')
    .copyDirectory('resources/assets/admin-lte/img', 'public/img')
    .copyDirectory('resources/assets/images', 'public/images')
	.copyDirectory('resources/assets/fonts', 'public/fonts')
	.copy('resources/assets/js/jquery-3.2.1.min.js', 'public/js/jquery-3.2.1.min.js')
    .copy('resources/assets/js/custom.js', 'public/js/custom.js')
    .copy('resources/assets/js/scripts.js', 'public/js/scripts.js')
	.copy('resources/assets/css/bootstrap.datepicker.css', 'public/css/bootstrap.datepicker.css')	
    .copy('resources/assets/js/bootstrap.datepicker.min.js', 'public/js/bootstrap.datepicker.min.js')
    .copy('resources/assets/css/bootstrap-tagsinput.css', 'public/css/bootstrap-tagsinput.css')
    .copy('resources/assets/js/bootstrap-tagsinput.min.js', 'public/js/bootstrap-tagsinput.min.js')
	.copy('resources/assets/css/jquery.datetimepicker.min.css', 'public/css/jquery.datetimepicker.min.css')	
    .copy('resources/assets/js/jquery.datetimepicker.min.js', 'public/js/jquery.datetimepicker.min.js')
    .copy('resources/assets/js/jquery.getAddress-2.0.1.min.js', 'public/js/jquery.getAddress-2.0.1.min.js')
