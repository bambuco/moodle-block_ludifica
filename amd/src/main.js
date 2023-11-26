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

/**
 * Javascript to initialise the block.
 *
 * @module    block/ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/str', 'core/modal_factory', 'block_ludifica/alertc', 'core/log'],
function($, str, ModalFactory, Alertc, Log) {

    // Load strings.
    var strings = [];
    strings.push({key: 'badgelinkcopiedtoclipboard', component: 'block_ludifica'});
    strings.push({key: 'showranking', component: 'block_ludifica'});
    strings.push({key: 'hideranking', component: 'block_ludifica'});

    var s = [];

    if (strings.length > 0) {

        strings.forEach(one => {
            s[one.key] = one.key;
        });

        str.get_strings(strings).then(function(results) {
            var pos = 0;
            strings.forEach(one => {
                s[one.key] = results[pos];
                pos++;
            });
            return true;
        }).fail(function(e) {
            Log.debug('Error loading strings');
            Log.debug(e);
        });
    }
    // End of Load strings.

    // Based in https://philipwalton.com/articles/responsive-components-a-solution-to-the-container-queries-problem/
    var resizeobserver = function() {

        // Find all elements with the `data-observe-resizes` attribute
        // and start observing them.
        $('[data-observe-resizes]').each(function() {

            // Only run if ResizeObserver is supported.
            if ('ResizeObserver' in self) {
                // Create a single ResizeObserver instance to handle all
                // container elements. The instance is created with a callback,
                // which is invoked as soon as an element is observed as well
                // as any time that element's size changes.
                let ro = new ResizeObserver(function(entries) {

                    // Default breakpoints that should apply to all observed
                    // elements that don't define their own custom breakpoints.
                    var defaultBreakpoints = {XS: 0, SM: 384, MD: 576, LG: 768, XL: 960};

                    entries.forEach(function(entry) {

                        // If breakpoints are defined on the observed element,
                        // use them. Otherwise use the defaults.
                        var breakpoints = entry.target.dataset.breakpoints ?
                            JSON.parse(entry.target.dataset.breakpoints) :
                            defaultBreakpoints;

                        // Update the matching breakpoints on the observed element.
                        Object.keys(breakpoints).forEach(function(breakpoint) {
                            var minWidth = breakpoints[breakpoint];
                            if (entry.contentRect.width >= minWidth) {
                                entry.target.classList.add(breakpoint);
                            } else {
                                entry.target.classList.remove(breakpoint);
                            }
                        });
                    });
                });

                ro.observe(this);
            }
        });
    };

    /**
     * Initialise all for the block.
     *
     */
    var init = function() {

        // Load default controls.

        // Modal.
        $('.block_ludifica-modal').each(function() {
            var $element = $(this);
            var title = $element.attr('title');
            var props = {
                title: title || '',
                body: $element.html()
            };

            if ($element.find('footer').length > 0) {
                props.footer = $element.find('footer');
            }

            if ($element.data('type')) {
                props.type = $element.data('type');
            }

            ModalFactory.create(props, $('.block_ludifica-modalcontroller[data-ref-id="' + $element.attr('id') + '"]'));
        });

        $('.block_ludifica-content').each(function() {

            var $blockcontent = $(this);

            // Tabs.
            $blockcontent.find('.block_ludifica-tabs').each(function() {
                var $tabs = $(this);
                var tabslist = [];

                $tabs.find('[data-ref]').each(function() {
                    var $tab = $(this);
                    tabslist.push($tab);

                    $tab.on('click', function() {
                        tabslist.forEach(one => {
                            $(one.data('ref')).removeClass('active showranking');
                        });

                        $tabs.find('.active[data-ref]').removeClass('active');

                        $tab.addClass('active');
                        $($tab.data('ref')).addClass('active');
                    });
                });

                // Load dynamic buttons.
                $blockcontent.find('[data-ludifica-tab]').each(function() {
                    var $button = $(this);

                    $button.on('click', function() {
                        var key = '.tab-' + $button.data('ludifica-tab');

                        tabslist.forEach($tab => {
                            if ($tab.data('ref').indexOf(key) >= 0) {
                                $tab.trigger('click');
                            }
                        });
                    });
                });
            });

            // Show ranking.
            $blockcontent.find('.showrankingbutton').each(function() {

                var isRankingShown = false;
                var $rankingbutton = $(this);
                var $tabranking = $blockcontent.find('.tabranking');

                if ($tabranking.length > 0) {
                    $rankingbutton.on('click', function(e) {
                        e.stopPropagation();

                        if (!isRankingShown) {

                            $($tabranking[0]).addClass('showranking');
                            $rankingbutton.addClass('activebtn');
                            $rankingbutton.text(s['hideranking']);

                        } else {
                            $blockcontent.find('.tabranking').removeClass('showranking');
                            $rankingbutton.removeClass('activebtn');
                            $rankingbutton.text(s['showranking']);
                        }

                        $blockcontent.find('.closeludifica').on('click', function() {
                            $blockcontent.find('.showranking').removeClass('showranking');
                            $blockcontent.find('.showrankingbutton').removeClass('activebtn');
                            $blockcontent.find('.showrankingbutton').text(s['showranking']);
                            isRankingShown = false;
                        });

                        isRankingShown = !isRankingShown;
                    });

                    $(document).on('click', function(e) {
                        var container = $blockcontent.find('.tabranking');

                        if (!container.is(e.target) && container.has(e.target).length === 0) {
                            if (isRankingShown) {
                                $blockcontent.find('.showranking').removeClass('showranking');
                                $blockcontent.find('.showrankingbutton').removeClass('activebtn');
                                $blockcontent.find('.showrankingbutton').text(s['showranking']);
                            }
                            isRankingShown = false;
                        }
                    });

                    $blockcontent.find('.shownexttop').on('click', function() {
                        var currentVisible = null;
                        $tabranking.each((index, onetab) => {
                            var $onetab = $(onetab);
                            if ($onetab.hasClass('showranking')) {
                                currentVisible = index;
                                $onetab.removeClass('showranking');
                            }
                        });
                        let nextVisible = (currentVisible >= $tabranking.length - 1) ? 0 : currentVisible + 1;
                        $($tabranking[nextVisible]).addClass('showranking');
                    });
                }

            });

            // Show helps
            $blockcontent.find('.showhelpsbutton').each(function() {

                var isHelpShown = false;
                var $helpbutton = $(this);

                $helpbutton.on('click', function(e) {
                    e.stopPropagation();

                    if (!isHelpShown) {
                        $blockcontent.find('.tab-dynamichelps').addClass('showhelps');
                        $(this).addClass('activebtn');
                        $(this).find('.pix-initial').hide();
                        $(this).find('.pix-toggle').show();
                    } else {
                        $blockcontent.find('.tab-dynamichelps').removeClass('showhelps');
                        $(this).removeClass('activebtn');
                        $(this).find('.pix-initial').show();
                        $(this).find('.pix-toggle').hide();
                    }

                    $blockcontent.find('.closeludifica').on('click', function() {
                        $('.showhelps').removeClass('showhelps');
                        $('.showhelpsbutton').removeClass('activebtn');
                        $('.showhelpsbutton').find('.pix-initial').show();
                        $('.showhelpsbutton').find('.pix-toggle').hide();
                        isHelpShown = false;
                    });

                    isHelpShown = !isHelpShown;
                });

                $(document).on('click', function(e) {
                    var container = $('.tab-dynamichelps');

                    if (!container.is(e.target) && container.has(e.target).length === 0) {
                        if (isHelpShown) {
                            $('.showhelps').removeClass('showhelps');
                            $('.showhelpsbutton').removeClass('activebtn');
                            $('.showhelpsbutton').find('.pix-initial').show();
                            $('.showhelpsbutton').find('.pix-toggle').hide();
                        }

                        isHelpShown = false;
                    }
                });
            });
        });

        $('body').on('updatefailed', '[data-inplaceeditable]', function(e) {
            var exception = e.exception; // The exception object returned by the callback.
            e.preventDefault(); // This will prevent default error dialogue.

            if (exception.errorcode == "nicknameexists") {
                Alertc.error(exception.message);

                // Cleared the error code because the event is twice called.
                exception.errorcode = null;
            }
        });

        resizeobserver();

    };

    return {
        init: init
    };

});
