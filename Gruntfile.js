// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/* jshint node: true, browser: false */
/* eslint-env node */

/**
 * Grunt configuration for ludifica block.
 *
 * @module    block/ludifica
 * @copyright 2023 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

"use strict";

/**
 * Grunt configuration.
 *
 * @param {Grunt} grunt
 */
module.exports = function(grunt) {

    var path = require('path'),
        PWD = process.env.PWD || process.cwd();

    var inAMD = path.basename(PWD) == 'amd';

    // Globbing pattern for matching all AMD JS source files.
    var amdSrc = [inAMD ? PWD + "/src/*.js" : "**/amd/src/*.js"];

    /**
     * Function to generate the destination for the uglify task
     * (e.g. build/file.min.js). This function will be passed to
     * the rename property of files array when building dynamically:
     * http://gruntjs.com/configuring-tasks#building-the-files-object-dynamically
     *
     * @param {String} destPath the current destination
     * @param {String} srcPath the  matched src path
     * @return {String} The rewritten destination path.
     */
    var uglifyRename = function(destPath, srcPath) {
        destPath = srcPath.replace("src", "build");
        destPath = destPath.replace(".js", ".min.js");
        destPath = path.resolve(PWD, destPath);
        return destPath;
    };

    grunt.initConfig({
        eslint: {
            options: {quiet: !grunt.option("show-lint-warnings")},
            amd: {src: amdSrc}
        },
        uglify: {
            amd: {
                files: [{
                    expand: true,
                    src: amdSrc,
                    rename: uglifyRename
                }],
                options: {
                    report: "min",
                    sourceMap: true
                }
            }
        },
        watch: {
            options: {
                nospawn: true,
                livereload: true
            },
            amd: {
                files: ["**/amd/src/**/*.js"],
                tasks: ["amd"]
            },
            css: {
                files: ["templates/*/styles.css", "styles.css"],
                tasks: ["stylelint"]
            }
        },
        stylelint: {
            css: {
                src: ["templates/*/styles.css", "styles.css"],
                options: {
                    configOverrides: {
                        rules: {
                            "at-rule-no-unknown": true,
                        }
                    }
                }
            }
        },
    });

    // Load contrib tasks.
    grunt.loadNpmTasks("grunt-contrib-watch");

    // Load core tasks.
    grunt.loadNpmTasks("grunt-contrib-uglify");
    grunt.loadNpmTasks("grunt-eslint");
    grunt.loadNpmTasks("grunt-stylelint");

    // Register tasks.
    grunt.registerTask("css", ["stylelint:css"]);
    grunt.registerTask("default", ["watch"]);

    // Register tasks.
    grunt.registerTask("amd", ["uglify"]);
    grunt.registerTask("default", ["watch"]);

    grunt.registerTask("compile", ["uglify"]);
};
