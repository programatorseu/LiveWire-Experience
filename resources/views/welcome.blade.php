<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link rel="stylesheet" href="/app.css">
        <script src="/app.js"></script>
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body>
        @livewire(App\Http\Livewire\Counter::class)

        <script>
            document.querySelectorAll('[wire\\:snapshot]').forEach(el => {
                let snapshot = JSON.parse(el.getAttribute('wire:snapshot'));
                console.log(snapshot);
            });
        </script>
    </body>
</html>
<?php 

?>