<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
    </head>

    <body>

        <div id="app" class="bg-light">

            @include('navbar')

            <div id="products-section" class="container py-5">
                @include('products')
            </div>

            <hr>

            <div id="checkout-section" class="container py-5">
                @include('checkout')
            </div>

            <hr>

            <div id="statistics-section" class="container py-5">
                @include('statistics')
            </div>

        </div>

        <script src="{{ mix('js/app.js') }}"></script>

    </body>

</html>
