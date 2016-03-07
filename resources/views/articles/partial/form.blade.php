<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
  <label for="title">{{ trans('forum.articles.form_title') }}</label>
  <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" class="form-control"/>
  {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
  <label for="tags">{{ trans('forum.articles.form_tags') }}</label>
  <select name="tags[]" id="tags" multiple="multiple" class="form-control" >
    @foreach($allTags as $tag)
      <option value="{{ $tag->id }}" {{ $article->tags->contains($tag->id) ? 'selected="selected"' : '' }}>{{ $tag->name }}</option>
    @endforeach
  </select>
  {!! $errors->first('tags', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
  <label for="content">{{ trans('forum.articles.form_content') }}</label>
  <textarea name="content" id="content" rows="10" class="form-control">{{ old('content', $article->content) }}</textarea>
  {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
  <div class="preview__content">
    {!! markdown(old('content', '...')) !!}
  </div>
</div>

<div class="form-group">
  <div class="checkbox">
    <label>
      <input type="checkbox" name="notification" value="{{ old('notification', 1) }}" {{ ($viewName == 'articles.create' or $article->notification) ? 'checked' : '' }}>
      {{ trans('forum.articles.notify_me') }}
    </label>
  </div>
</div>

<div class="form-group">
  <label for="my-dropzone">{{ trans('forum.articles.form_files') }}
    <small class="text-muted"><i class="fa fa-chevron-down"></i> {{ trans('forum.articles.open_files') }}</small>
    <small class="text-muted" style="display: none;"><i class="fa fa-chevron-up"></i> {{ trans('forum.articles.close_files') }}</small>
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
        "<h2>{{ trans('forum.articles.dz_drop') }}</h2>" +
        "<p>{{ trans('forum.articles.dz_click') }}</p></div>",
      dictFileTooBig: "{{ trans('forum.articles.dz_toobig') }}",
      dictInvalidFileType: '{{ trans('forum.articles.dz_filetype') }}',
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
      placeholder: '{{ trans('forum.articles.s2_select') }}',
      maximumSelectionLength: 3,
      language: {
        maximumSelected: function(args) {
          return args.maximum + '{{ trans('forum.articles.s2_max') }}';
        }
      }
    });
  </script>
@stop