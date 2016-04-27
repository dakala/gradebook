/**
 * @file
 * Javascript for the node content editing form.
 */

(function ($) {

    'use strict';

    /**
     * Behaviors for setting summaries on content type form.
     *
     * @type {Drupal~behavior}
     *
     * @prop {Drupal~behaviorAttach} attach
     *   Attaches summary behaviors on content type edit forms.
     */
    Drupal.behaviors.gradebookContentTypes = {
        attach: function (context) {
            var $context = $(context);
            // Provide the vertical tab summaries for gradebook.
            $context.find('#edit-gradebook').drupalSetSummary(function (context) {
                var vals = [];
                var $editContext = $(context);

                $editContext.find('input:checked').next('label').each(function () {
                    vals.push(Drupal.checkPlain($(this).text()));
                });
                if (!$(context).find('#edit-gradebook-node-type').is(':checked')) {
                    vals.unshift(Drupal.t('Not enabled'));
                }
                return vals.join(', ');
            });
        }
    };

})(jQuery);
