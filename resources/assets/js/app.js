(function() {

  var App = {};

  App.init = function () {
    this._bind = function (fn, me) {
      return function () {
        return fn.apply(me, arguments);
      };
    };

    this.registerGlobals();
    this.manipulateUi();
    this.handleTextareas();
    this.registerListeners();

    /* Activate syntax highlight.
     This will affect code blocks right after the page renders */
    hljs.initHighlightingOnLoad();
  };

  App.registerGlobals = function() {
    window.csrfToken = $('meta[name="csrf-token"]').attr('content');

    /* Set Ajax request header.
     Document can be found at http://laravel.com/docs/5.1/routing#csrf-x-csrf-token */
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': window.csrfToken
      }
    });
  };

  App.manipulateUi = function() {
    if($('.alert')) {
      $('.alert').delay(5000).fadeOut();
    }

    if ($('#flash-overlay-modal')) {
      $('#flash-overlay-modal').modal();
    }
  };

  App.handleTextareas = function() {
    var textAreas = $('textarea');

    if (textAreas.length) {
      /* Activate Tabby on every textarea element */
      textAreas.tabby({tabString: '    '});

      /* Auto expand textarea size */
      autosize(textAreas);

      textAreas.on('focus', function (e) {
        // Show preview pane when a textarea is in focus
        var el = $(this).siblings('div.preview__content').first();

        if (! el.html().length) {
          el.html('...');
        }

        el.show();
      });

      textAreas.on('keyup', function(e) {
        // Register 'keyup' event handler
        var self = $(this),
          content = self.val(),
          previewEl = self.siblings("div.preview__content").first();

        // Compile textarea content
        var compiled = marked(content, {
          renderer: new marked.Renderer(),
          gfm: true,
          tables: true,
          breaks: true,
          pedantic: false,
          sanitize: true,
          smartLists: true,
          smartypants: false
        });

        // Fill preview container with compiled content
        previewEl.html(compiled);
        // Add syntax highlight on the preview content
        previewEl.find('pre code').each(function(i, block) {
          hljs.highlightBlock(block)
        });
      }).trigger('keyup');
    }
  };

  App.registerListeners = function() {
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
  };

  $(function() {
    return App.init();
  });

}).apply(this);

/* Global Helper Functions */

/* Generate flash message from javascript */
function flash(type, msg, delay) {
  var el = $("div.js-flash-message");

  if (el) {
    el.remove();
  }

  $("<div></div>", {
    "class": "alert alert-" + type + " alert-dismissible js-flash-message",
    "html": '<button type="button" class="close" data-dismiss="alert">'
    + '<span aria-hidden="true">&times;</span>'
    + '<span class="sr-only">Close</span></button>' + msg
  }).appendTo($(".container").first());

  $("div.js-flash-message").fadeIn("fast").delay(delay || 5000).fadeOut("fast");
}

/* Reload page */
function reload(interval) {
  setTimeout(function () {
    window.location.reload(true);
  }, interval || 5000);
}