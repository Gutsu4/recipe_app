<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Vue App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/js/app.ts', 'resources/css/app.css'])
</head>
<body>
<div id="app"></div>
</body>
</html>
