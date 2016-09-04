<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>라라벨 입문</title>
    {{--페이지 30 코드 5-10 여러개의 섹션을 이용하는 예제를 실험하기 위해 추가--}}
    @yield('style')
  </head>
  <body>
    @yield('content')

    {{--페이지 30 코드 5-10 여러개의 섹션을 이용하는 예제를 실험하기 위해 추가--}}
    @yield('script')
  </body>
</html>