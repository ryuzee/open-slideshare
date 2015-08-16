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

    grunt.registerTask('default', ['watch']);
};
