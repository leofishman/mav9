(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.header = {
    attach: function (context) {
      var timer;

      // Open Header Dropdown Menu.
      function openMenu(t) {
        var tLink = t[0].children[0];
        var tMenu = $(tLink).siblings('.header__submenu-items');
        var menuItems = t.siblings('.header__menu-item--expanded');

        t.attr('aria-expanded', true);
        $(tLink).addClass('active');
        tMenu.addClass('active');

        // Close other dropdown menus.
        $.each(menuItems, function (i, val) {
          if ($(menuItems[i]).attr('aria-expanded') == 'true') {
            var menuLinks = $(menuItems[i]).find('.header__menu-link--toggle');
            var menus = menuLinks.siblings('.header__submenu-items');

            $(menuItems[i]).attr('aria-expanded', false);
            menuLinks.removeClass('active');
            menus.removeClass('active');
          }
        });
      }

      // Open Header Dropdown Menu.
      function closeMenu(t) {
        var tLink = t[0].children[0];
        var tMenu = $(tLink).siblings('.header__submenu-items');

        t.attr('aria-expanded', false);
        $(tLink).removeClass('active');
        tMenu.removeClass('active');
      }

      $('.header__menu-item--expanded', context)
        .on('mouseover focusin', function (e) {
          clearTimeout(timer);
          openMenu($(e.currentTarget));
        })
        .on('mouseleave', function (e) {
          timer = setTimeout(closeMenu($(e.currentTarget)), 1000);
        });

      // Toggles Mobile Menu.
      $('.header__btn', context).on('click', function (e) {
        var targetBtn = $(e.currentTarget);
        var targetMenu = $('.header__content--mobile', context);

        if (targetBtn.attr('aria-expanded') === 'false') {
          targetBtn.attr('aria-expanded', true);
        }
        else {
          targetBtn.attr('aria-expanded', false);
        }

        $('.header__btn-icon', context).toggleClass('active');
        targetMenu.toggleClass('active');
      });

      // Toggles Mobile Submenus.
      $('.header__menu-link--toggle', context).on('click', function (e) {
        e.preventDefault();
        var targetLink = $(e.currentTarget);
        var targetID = targetLink.attr('aria-controls');
        var targetMenu = $('#' + targetID, context);
        var targetIcons = targetLink.children('.header__menu-icon');

        var targetMenuItem = targetLink.closest('.header__menu-item--expanded');
        var menuItems = targetMenuItem.siblings('.header__menu-item--expanded');
        var openIcons = menuItems.find('.header__menu-icon--open');
        var closeIcons = menuItems.find('.header__menu-icon--close');
        var menus = menuItems.children('.header__menu-items--mobile');

        // Display submenu.
        targetIcons.toggleClass('active');
        targetMenu.toggleClass('active');

        if (targetLink.attr('aria-expanded') == 'false') {
          // Close other menus.
          targetLink.attr('aria-expanded', true);
          openIcons.addClass('active');
          closeIcons.removeClass('active');
          menuItems.attr('aria-expanded', false);
          menus.removeClass('active');
          targetLink.siblings('.header__menu-icon--close').addClass('active');
          targetMenuItem.attr('aria-expanded', true);
        }
        else {
          targetLink.siblings('.header__menu-icon--open').addClass('active');
          targetMenuItem.attr('aria-expanded', false);
        }
      });
    }
  };
})(jQuery, Drupal);
