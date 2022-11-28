<div class="search-wrapper">
    <div id="search-overlay" class="search-overlay search-overlay-hidden">
        <div class="search-form-container">
            <span class="search-close"><i class="fas fa-times fa-lg"></i></span>
            <form action="{{ route('web.blog.posts') }}" method="GET">
                <div class="search-form-input-wrapper">
                    <input name="search" type="text" id="search-form-input" placeholder="Search ..." required="required" autofocus="autofocus">
                    <span class="search-form-input-bottom-bar"></span>
                    <input type="submit" hidden />
                </div>
            </form>
        </div>
    </div>
</div>
