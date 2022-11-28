<ul>
    @foreach ($categories as $category)
        <li>
            <a href='{{ route('web.blog.category-posts.show', $category->slug) }}' title='{{ $category->title }}' class='{{ $category->deviconClass() }} without-style'>
                @if ($category->hasImage() && empty($category->deviconClass()))
                    <img class='img-icon' src='{{ $category->icon_url }}'>
                @endif
            </a>
        </li>
    @endforeach
</ul>
