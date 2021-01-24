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
        }
    });

    // Plugin-ins laden
    grunt.loadNpmTasks('grunt-contrib-uglify');

    // Grunt Tasks ausführen, weitere in uglify array
    grunt.registerTask('default', ['uglify']);

};