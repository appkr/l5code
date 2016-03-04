@extends('layouts.master')

@section('style')
  <style>
    body {background: black; color: white;}
  </style>
@endsection

@section('content')
  <p>저는 자식 뷰의 'content' 섹션입니다.</p>

  @include('partials.footer')
@endsection

@section('script')
  <script>
    alert("저는 자식 뷰의 'script' 섹션입니다.");
  </script>
@endsection
