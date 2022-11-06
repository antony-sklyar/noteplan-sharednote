<x-published title="{{ $title }}">

	<!-- TODO: note content should be decrypted and rendered properly -->
    {!! Illuminate\Mail\Markdown::parse($content) !!}

</x-published>