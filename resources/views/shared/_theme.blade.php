<script>
    ;(function() {
        window.__onThemeChange = function(theme) {}

        let usedTheme

        function setTheme(newTheme) {
            window.__theme = newTheme
            usedTheme = newTheme
            document.body.setAttribute('data-theme', newTheme)
            window.__setIconTheme(newTheme)
            window.__onThemeChange(newTheme)
        }

        try {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                usedTheme = 'dark'
            }

            usedTheme = localStorage.getItem('theme')
        } catch (err) {}

        window.__setTheme = function(newTheme) {
            setTheme(newTheme)
            try {
                localStorage.setItem('theme', newTheme)
            } catch (err) {}
        }

        setTheme(usedTheme || 'light')
    })()
</script>
