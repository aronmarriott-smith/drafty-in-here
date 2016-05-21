module.exports = function(grunt) {
    grunt.initConfig({
        makepot: {
            target: {
                options: {
                    cwd: '',                          // Directory of files to internationalize.
                    domainPath: '',                   // Where to save the POT file.
                    exclude: ['tests/.*','dotorg/.*'],
                    include: ['drafty-main.php','Repositories/.*', 'partials/.*'],
                    mainFile: 'drafty-in-here.php',   // Main project file.
                    potComments: '',                  // The copyright at the beginning of the POT file.
                    potFilename: '',                  // Name of the POT file.
                    potHeaders: {
                        poedit: true,                 // Includes common Poedit headers.
                        'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
                    },                                // Headers to add to the generated POT file.
                    processPot: null,                 // A callback function for manipulating the POT file.
                    type: 'wp-plugin',                // Type of project (wp-plugin or wp-theme).
                    updateTimestamp: true,            // Whether the POT-Creation-Date should be updated without other changes.
                    updatePoFiles: false              // Whether to update PO files in the same directory as the POT file.
                }
            }
        }
    });


    grunt.loadNpmTasks( 'grunt-wp-i18n' );

};