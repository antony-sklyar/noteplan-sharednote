<x-published title="Sample Published Note">
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>

    <small class="text-gray-400 text-right my-3 block">
        Published on 2022-11-11 11:11:11
    </small>

    <div class="max-w-4xl mx-auto my-7 bg-white rounded-xl shadow-lg p-6 published-note">
        <h1>This is a sample published note</h1>
        <p>Here is a <mark>line</mark> with <strong>basic</strong> formatting <i>that</i> <u>you</u> would <strike>not</strike> expect.</p>
        <p>Here is a second line.</p>

        <h2>Subheading 2</h2>
        <ul>
            <li class="task">Incomplete task <span class="scheduled">&gt;today</span></li>
            <li class="task completed">Completed task</li>
            <li class="task canceled">Canceled task</li>
            <li class="task scheduled">Scheduled task <span class="scheduled">&gt;2022-11-11</span></li>
        </ul>
        <h3>Subheading 3</h3>
        <ul>
            <li>List</li>
            <li>List</li>
            <li>List</li>
        </ul>

        <h4>Subheading 4</h4>
        <ol>
            <li>Number</li>
            <li>Number</li>
            <li>Number</li>
        </ol>

        <hr>

        <p>Some <code>inline</code> code under the separator.</p>

        <blockquote>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed luctus justo at nibh mattis, ut volutpat lorem ullamcorper. Quisque laoreet nisi nisi, pellentesque molestie eros convallis nec. Donec commodo nibh leo, ut ullamcorper nisl molestie nec. Curabitur massa erat, bibendum venenatis egestas in, eleifend facilisis odio. Phasellus quam lacus, porta tincidunt sem vitae, tempus pharetra tortor. Cras in erat nec purus eleifend lacinia vitae ac sem. Aliquam tincidunt tincidunt pellentesque. Nullam pharetra magna vitae pharetra pulvinar. Suspendisse a velit et mauris accumsan venenatis vel accumsan tortor. Sed nec velit eros. Nunc accumsan ipsum non pulvinar tempor.</blockquote>

        <pre>var some = "code";
foreach (var o in everything) some += 'thing';</pre>
    </div>

</x-published>