<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? config('app.name') }}</title>

        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        <livewire:styles />

        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    </head>

    <body class="antialiased">
        {{ $body }}
    </body>

    <livewire:scripts />
</html>

