<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>{{ isset($title) ? $title . ' |' : '' }} @settings('app_name')</title>

    <!-- Shared assets -->



<link rel="icon" type="image/x-icon" href="@settings('logo_url')" />

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
crossorigin="anonymous"
referrerpolicy="no-referrer">

@vite(['resources/css/core/core.css'])

<script src="https://cdn.tiny.cloud/1/oe2xz52b1wiacx1ys8xzk5j31bm7r9xsa7ulmo2jg1ikr64l/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
@vite(['resources/js/app.js'])
</head>

<body>


    <main class="container">
        @yield('pageContent')
    </main>


</body>

</html>
