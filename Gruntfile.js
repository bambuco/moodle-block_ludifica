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

    // We need to include the core Moodle grunt file too, otherwise we can't run tasks like "amd".
    require("grunt-load-gruntfile")(grunt);
    grunt.loadGruntfile("../../Gruntfile.js");

    // Load all grunt tasks.
    grunt.loadNpmTasks("grunt-stylelint");
    grunt.loadNpmTasks("grunt-eslint");

    grunt.initConfig({
        eslint: {
            options: {quiet: !grunt.option("show-lint-warnings")},
            amd: {src: amdSrc}
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
    grunt.loadNpmTasks("grunt-eslint");
    grunt.loadNpmTasks("grunt-stylelint");

    // Register tasks.
    grunt.registerTask("css", ["stylelint:css"]);
    grunt.registerTask("default", ["watch"]);

};
