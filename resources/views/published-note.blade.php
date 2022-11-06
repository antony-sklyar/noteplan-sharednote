<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
	    <link rel="icon" href="/favicon.ico"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $note->title }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased px-5 mx-auto my-10 max-w-4xl">

        	@unless ($password)
        		<form class="max-w-md mx-auto bg-white rounded-xl shadow-lg flex items-center space-x-3 p-3 my-20">
        			<input type="password" name="password" placeholder="Please enter password to access note content"
        			       class="rounded px-3 py-1 border-slate-300 grow"
        			>
        			<button type="submit" class="bg-amber-400 rounded px-3 py-1">Open</button>
    			</form>
        	@else
	        	<!-- TODO: note content should be decrypted and rendered properly -->
	            {!! Illuminate\Mail\Markdown::parse($note->content) !!}
            @endif
        </div>
    </body>
</html>
