module.exports = function(grunt) {

    // config
    grunt.initConfig({
        uglify: {
            options: {
                mangle: false
            },
            my_target: {
                files: {
                // 'path/output.min.js' : ['path/input.js']
                    'amd/build/steps_view.min.js': ['amd/src/steps_view.js']
                }
            }
        },
        watch: {
            scripts: {
                files: ['amd/src/*.js'],
                tasks: ['uglify'],
                options: {
                    spawn: true,
                },
            },
        }
    });


    // Plugin-ins laden
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Grunt Tasks ausführen, weitere in uglify array
    grunt.registerTask('default', ['uglify']);

};