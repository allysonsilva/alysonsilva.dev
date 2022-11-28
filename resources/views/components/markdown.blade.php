{{-- <x-markdown>
# This is cool!

**Markdown** rendered from our custom _component_!
</x-markdown> --}}

<div>
    {!! $parseMarkdown($slot) !!}
</div>
