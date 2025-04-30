<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel + Vue 3</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app" data-prefetch='@json($data ?? [])'></div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
