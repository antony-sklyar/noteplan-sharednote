<x-published title="{{ $title }}">
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>

    <small class="text-gray-400 text-right my-3 block">
        Published on
        <span x-data="{ date: moment.utc('{{ $updated_at }}').local().format('YYYY-MM-DD HH:mm:ss') }" x-text="date">test</span>
    </small>

    <div class="max-w-4xl mx-auto my-7 bg-white rounded-xl shadow-lg p-6 published-note">
        {!! $content !!}
    </div>

</x-published>