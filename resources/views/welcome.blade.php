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
                el.addEventListener('click', e => {
                    if(! e.target.hasAttribute('wire:click')) return 
                    let method = e.target.getAttribute('wire:click');
             
                    fetch('/livewire', {
                        method: 'POST',
                        headers: {'Content-Type':'application/json'},
                        body: JSON.stringify({
                            snapshot: snapshot,
                            callMethod: method
                        })
                    });
                });
            });
        </script>
    </body>
</html>
<?php 

?>