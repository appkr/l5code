(function() {

  var Forum = {};

  Forum.init = function () {
    this._bind = function (fn, me) {
      return function () {
        return fn.apply(me, arguments);
      };
    };

    this.handleTextareas();
  };

  Forum.handleTextareas = function() {
    var textAreas = $('textarea');

    if (textAreas.length) {
      // 모든 textarea에 Tabby(탭 들여쓰기) 기능 활성화
      // 탭 사이즈는 4 스페이스로
      textAreas.tabby({tabString: '    '});

      // 모든 textarea에 autosize(글의 길이에 따라 textarea 크기가 자동으로 늘어남) 기능 활성화
      autosize(textAreas);

      textAreas.on('focus', function (e) {
        // 미리 보기 컨테이너 초기화
        var el = $(this).siblings('div.preview__content').first();

        if (! el.html().length) {
          el.html('...');
        }

        el.show();
      });

      textAreas.on('keyup', function(e) {
        // 'keyup' 이벤트 핸들러 등록
        var self = $(this),
          content = self.val(),
          previewEl = self.siblings("div.preview__content").first();

        // textarea 내용을 HTML로 컴파일
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

        // 미리보기 컨테이너에 컴파일된 HTML 추가
        previewEl.html(compiled);
        // 신택스 하이라이트 적용
        previewEl.find('pre code').each(function(i, block) {
          hljs.highlightBlock(block)
        });
      }).trigger('keyup');
    }
  };

  $(function() {
    return Forum.init();
  });

}).apply(this);