<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
  <label for="title">제목</label>
  <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" class="form-control"/>
  {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
  <label for="tags">태그</label>
  <select name="tags[]" id="tags" multiple="multiple" class="form-control" >
    @foreach($allTags as $tag)
      <option value="{{ $tag->id }}" {{ $article->tags->contains($tag->id) ? 'selected="selected"' : '' }}>{{ $tag->name }}</option>
    @endforeach
  </select>
  {!! $errors->first('tags', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
  <label for="content">본문</label>
  <textarea name="content" id="content" rows="10" class="form-control">{{ old('content', $article->content) }}</textarea>
  {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group">
  <div class="checkbox">
    <label>
      <input type="checkbox" name="notification" value="{{ old('notification', 1) }}" {{ ($viewName == 'articles.create' or $article->notification) ? 'checked' : '' }}>
      댓글이 작성되면 이메일 알림 받기
    </label>
  </div>
</div>

<div class="form-group">
  <label for="my-dropzone">파일
    <small class="text-muted"><i class="fa fa-chevron-down"></i> 열기</small>
    <small class="text-muted" style="display: none;"><i class="fa fa-chevron-up"></i> 닫기</small>
  </label>
  <div id="my-dropzone" class="dropzone"></div>
</div>

@section('script')
  @parent
  <script>
    var form = $('form').first(),
      dropzone  = $('div.dropzone'),
      dzControl = $('label[for=my-dropzone]>small');

    /* Dropzone */
    Dropzone.autoDiscover = false;

    // Dropzone instantiation.
    var myDropzone = new Dropzone('div#my-dropzone', {
      url: '/attachments',
      paramName: 'files',
      maxFilesize: 3,
      acceptedFiles: '.{{ implode(',.', config('project.mimes')) }}',
//      acceptedFiles: '.jpg,.png,.zip,.tar',
      uploadMultiple: true,
      params: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        article_id: '{{ $article->id }}'
      },
      dictDefaultMessage: '<div class="text-center text-muted">' +
        '<h2>첨부할 파일을 끌어다 놓으세요!</h2>' +
        '<p>(또는 클릭하셔도 됩니다.)</p></div>',
      dictFileTooBig: '파일당 최대 크기는 3MB입니다.',
      dictInvalidFileType: 'jpg, png, zip, tar 파일만 가능합니다.',
      addRemoveLinks: true
    });

    // Insert image markdown at caret position of the content.
    var handleContent = function(objId, imgUrl, remove) {
      var caretPos = document.getElementById(objId).selectionStart;
      var content = $('#' + objId).val();
      var imgMarkdown = '![](' + imgUrl + ')';

      if (remove) {
        $('#' + objId).val(
          content.replace(imgMarkdown, '')
        );

        return;
      }

      $('#' + objId).val(
        content.substring(0, caretPos) +
        imgMarkdown + '\n' +
        content.substring(caretPos)
      );
    };

    // Add or remove hidden 'attachments[]' input element.
    var handleFormElement = function(id, remove) {
      if (remove) {
        $('input[name="attachments[]"][value="'+id+'"]').remove();

        return;
      }

      $('<input>', {
        type: 'hidden',
        name: 'attachments[]',
        value: id
      }).appendTo(form);
    }

    // Success event listener for file upload.
    myDropzone.on('successmultiple', function(file, data) {
      for (var i= 0,len=data.length; i<len; i++) {
        // Append 'attachments[]' hiddne input.
        // Extracted to handleFormElement function.
//        $('<input>', {
//          type: 'hidden',
//          name: 'attachments[]',
//          value: data[i].id
//        }).appendTo(form);
        handleFormElement(data[i].id);

        // Add attributes to file(Dropzone's File instance).
        file[i]._id = data[i].id;
        file[i]._name = data[i].filename;
        file[i]._url = data[i].url;

        // Call handleContent() if the given data[i] is an image file.
        if (/^image/.test(data[i].mime)) {
          handleContent('content', data[i].url);
        }
      }
    });

    // Event listener for file remove.
    myDropzone.on('removedfile', function(file) {
      // When a user tries to remove a file from the Dropzone UI,
      // the image will disappear in DOM level, but not in the service
      // So, we send ajax to the server to request model/file destroy.
      $.ajax({
        type: 'POST',
        url: '/attachments/' + file._id,
        data: {
          _method: 'DELETE'
        }
      }).success(function(data) {
        handleFormElement(data.id, true);

        if (/^image/.test(data.mime)) {
          handleContent('content', data.url, true);
        }
      })
    });

    // Toggle dropzone UI.
    dzControl.on('click', function(e) {
      dropzone.fadeToggle(0);
      dzControl.fadeToggle(0);
    });

    /* select2 */
    $('#tags').select2({
      placeholder: '태그를 선택하세요 (최대 3개)',
      maximumSelectionLength: 3,
      language: {
        maximumSelected: function(args) {
          return '태그는 최대 ' + args.maximum + '개까지만 선택할 수 있습니다.';
        }
      }
    });
  </script>
@stop