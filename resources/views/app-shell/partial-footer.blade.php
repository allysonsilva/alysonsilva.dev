            </section>
        </main>

        @include('layouts.partials._footer')
    </section>
    <!-- // Site Wrapper -->

    <!-- Scripts -->
        <script src="{{ mix('site/js/vendors-svg.js') }}" defer></script>
        <script src="{{ mix('site/js/header.js') }}" defer></script>

        <script src="{{ mix('site/js/manifest.js') }}" defer></script>

        <script type="module" src="{{ mix('site/js/app.js') }}"></script>
    <!-- // Scripts -->
</body>
</html>
