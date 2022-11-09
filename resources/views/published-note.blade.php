<x-published title="{{ $title }}">
    <small class="text-gray-400 text-right my-3 block">Published on {{ $updated_at }}</small>

    <div class="max-w-4xl mx-auto my-7 bg-white rounded-xl shadow-lg p-6 published-note">
        {!! $content !!}
    </div>

</x-published>