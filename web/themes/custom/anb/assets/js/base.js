/**
 * @file
 */
(function ($, Drupal) {

  'use strict';

  /**
   * Mobile menu.
   */
  $('.header-menu__trigger, .header-menu__close').click(function () {
    $('.header-menu').toggleClass('is-visible');
  })

  /**
   * User menu dropdown
   */
  const userDropBtn = document.getElementById('userDropBtn');
  const accountDropdown = document.getElementById('accountDropdown');
  if (userDropBtn && accountDropdown) {
    userDropBtn.addEventListener('click', () => {
      accountDropdown.classList.toggle('show');
    })
    // Close the dropdown if the user clicks outside of it
    window.onclick = function (event) {
      if (!event.target.matches('#userDropBtn')) {
        if (accountDropdown.classList.contains('show')) {
          accountDropdown.classList.remove('show');
        }
      }
    }
  }

  $(document).ready(function () {
    /**
     * Fancybox.
     */
    $('[data-fancybox]').fancybox({
      buttons: ['close'],
    });
  });

})(jQuery, Drupal);

(function ($, Drupal) {
  Drupal.behaviors.base = {
    attach: function (context, settings) {
      /**
       * Custom buttons.
       */
      let formSubmit = $('.form-submit');
      formSubmit.each(function () {
        if ($(this).parents('.submit-wrap').length === 0) {
          $(this).wrap('<div class="submit-wrap"></div>');
          $('<span class="button button--fake">' + $(this).attr('value') + '</span>').insertBefore($(this));
        }
      })
      let fakeButton = $('.button--fake');
      fakeButton.click(function () {
        $(this).siblings('input[type="submit"]').click();
      });

      /**
       * Equipment slider.
       */
      $('.equipment__items', context).slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        dots: true,
        responsive: [
          { breakpoint: 960, settings: { slidesToShow: 2, slidesToScroll: 2 } },
          { breakpoint: 720, settings: { slidesToShow: 1, slidesToScroll: 1, arrows: false } },
        ]
      });
      /**
       * Sliders customization.
       */
      let sliders = $('.slick-slider');
      let arrow = '<svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 11L6 6L1 1" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

      sliders.each(function () {
        if ($(this).find('.slider-nav').length === 0) {
          let navTemplate = '<div class="slider-nav"><div class="slider-nav__inner container"></div></div>';
          $(this).append(navTemplate);

          $(this).find('.slider-nav__inner').prepend($(this).find('.slick-prev').html(arrow));
          $(this).find('.slick-dots').insertAfter($(this).find('.slider-nav__pager'));
          $(this).find('.slider-nav__inner').append($(this).find('.slick-next').html(arrow));
        }
      })

      /**
       * Phone input mask.
       */
      let phone_fields = $('input[name*="phone"]');
      phone_fields.attr('type', 'tel').mask('+7 (999) 999-99-99');

      /**
       * Modal forms.
       */
      $ = jQuery;
      function show_form(form_id) { show_modals(); $(form_id).addClass('is-visible'); };
      function show_modals() { $('.modals').addClass('is-visible'); };
      $('.modal-overlay, .modal-form__close').click(function () { $('.modals, .modal-form').removeClass('is-visible'); });
      $('.modal-trigger').click(function () { show_form('#' + $(this).attr('data-modal')); });

      /**
       * Tabs.
       */
      $ = jQuery; $('.tab').click(function () { tabId = $(this).attr('data-tab'); $(this).parents('.tabsgroup').find('.tab').removeClass('is-active'); $(this).addClass('is-active'); $(this).parents('.tabsgroup').find('.tabcontent').removeClass('is-visible'); $(this).parents('.tabsgroup').find('.tabcontent[data-tab="' + tabId + '"]').addClass('is-visible'); }); $('.tabsgroup').each(function () { if ($(this).find('.tab.is-active').length === 0) { $(this).find('.tab').first().click(); } }); var url = document.location.href; var hash = url.substring(url.indexOf("#") + 1); $('.tab[data-tab="' + hash + '"]').click();
    }
  };
})(jQuery, Drupal);
