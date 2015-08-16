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
        clean: ["app/tmp/logs/*", "!app/tmp/**/.gitkeep"],
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

    grunt.registerTask('default', ['watch']);
};
