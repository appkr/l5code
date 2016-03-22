$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

hljs.initHighlightingOnLoad();

/* At the time of page loading, remove any element having flash-message class in 5 secs */
if($(".alert")) {
  $(".alert").delay(5000).fadeOut();
}

if ($("#flash-overlay-modal")) {
  $("#flash-overlay-modal").modal();
}