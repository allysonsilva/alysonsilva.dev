<script>
window.addEventListener('DOMContentLoaded', (event) => {
    document.head.insertAdjacentHTML('beforeend', String.raw`{!! $seoHTML !!}`);

    let script = document.createElement('script');
    script.type = 'application/ld+json';
    script.innerHTML = String.raw`
        @include('schemas.home')
    `;

    document.head.appendChild(script);
});
</script>

@include('pages.shared._home-content')
