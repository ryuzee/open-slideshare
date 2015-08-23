module.exports = function(grunt){
    grunt.initConfig({
        cssmin: {
            target: {
                expand: true,
                src: ['app/webroot/css/**/openslideshare*.css', '!*.min.css'],
                dest: './',
                ext: '.min.css'
            }
        },
        csslint: {
            strict: {
                options: {
                    import: 2
                },
                src: ['app/webroot/css/**/openslideshare*.css', '!app/webroot/**/*.min.css']
            },
            lax: {
                options: {
                    import: false
                },
                src: ['app/webroot/css/**/openslideshare*.css', '!app/webroot/**/*.min.css']
            }
        },
        clean: ["app/tmp/logs/*", "app/reports/*", "!app/tmp/**/.gitkeep"],
        shell: {
            reloadx: {
                command: './node_modules/.bin/livereloadx'
            },
            run_test: {
                command: 'app/Console/cake test --log-junit app/reports/unittest.xml app AllTests'
            },
            run_test_full: {
                command: 'app/Console/cake test --configuration=oss_phpunit.xml app AllTests'
            },
            run_phpcs: {
                command: './Vendor/bin/phpcs app/**/*.php --standard=PSR2;:'
            }
        },
        watch: {
            css: {
                files: ['app/webroot/css/**/*.css'],
                tasks: ['cssmin']
            },
            script: {
                files: ['app/webroot/js/**/*.js'],
                tasks: ['uglify']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-csslint');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-shell');

    grunt.registerTask('default', ['watch']);
};
