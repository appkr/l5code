/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
  el: 'body',

  ready() {
    hljs.initHighlightingOnLoad();
    this.removeFlashMessages();
    this.setJqueryAjaxHeaders();
    this.initBackToTopButton();
  },

  methods: {
    removeFlashMessages() {
      if ($('.alert')) {
        $('.alert').delay(5000).fadeOut();
      }
    },

    setJqueryAjaxHeaders() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    },

    initBackToTopButton() {
      $('#back-to-top').on('click', function () {
        $('body,html').animate({
          scrollTop: 0
        }, 800);

        return false;
      });

      $(window).on('scroll', function () {
        var scrollPos = $(window).scrollTop();

        if (scrollPos > 50) {
          $('#back-to-top').fadeIn();
        } else {
          $('#back-to-top').fadeOut();
        }
      });
    }
  }
});
