/**
 * Pairs a link and a block of content. The link will toggle the appearance of
 * that block content
 *
 */
(function ($) {
  Drupal.behaviors.os_toggle = {
    attach: function (ctx) {
      $('.toggle', ctx).click(function(event) {
        event.preventDefault();

        $(this).toggleClass("expanded");
        var slider = null;

        $(this).add($(this).parents()).each(function () {
          var potentials = $(this).siblings('.os-slider');
          if (potentials.length) {
            slider = $(potentials[0]);
          }
        });

        if ($(this).hasClass('toggle') && $(this).hasClass('expanded')) {
          return;
        }

        if ($.browser.msie == undefined) {
          slider.slideToggle("fast");
        }
        else {
          // IE8 Does not work with the slider.
          if ($(this).hasClass('expanded')) {
          slider.show();
          }
          else {
            slider.hide();
          }
        }
      });
    }
  };
})(jQuery);
