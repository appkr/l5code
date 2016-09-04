{{--
<!--코드 5-1-->
<h1>
  {{  $greeting or 'Hello' }}
  {{  $name or '' }}
</h1>
--}}

{{--
<!--코드 5-5-->
@if($itemCount = count($items))
  <p>{{ $itemCount }} 종류의 과일이 있습니다.</p>

  <ul>
    <!--코드 5-6-->
    @foreach($items as $item) \
      <li>{{ $item }}</li>
    @endforeach
  </ul>
@else
  <p>엥~ 아무것도 없는데요!</p>
@endif
--}}

{{--
<!--코드 5-7-->
@php
  $items = [];
@endphp

<ul>
  @forelse($items as $item)
    <li>{{ $item }}</li>
  @empty
    <li>엥~ 아무것도 없는데요!</li>
  @endforelse
</ul>
--}}

{{--
<!--코드 5-9-->
@extends('layouts.master')

@section('content')
  <p>저는 자식 뷰의 'content' 섹션입니다.</p>
@endsection
--}}

{{--
<!--코드 5-10-->
@extends('layouts.master')

@section('style')
  <style>
    body {background: green; color: white;}
  </style>
@endsection

@section('content')
  <p>저는 자식 뷰의 'content' 섹션입니다.</p>
@endsection

@section('script')
  <script>
    alert("저는 자식 뷰의 'script' 섹션입니다."); </script>
@endsection
--}}

{{--
<!--코드 5-12-->
@extends('layouts.master')

@section('content')
  @include('partials.footer')
@endsection
--}}

<!--코드 5-13-->
@extends('layouts.master')

@section('content')
  @include('partials.footer')
@endsection

@section('script')
  <script>
    alert("저는 자식 뷰의 'script' 섹션입니다.");
  </script>
@endsection
