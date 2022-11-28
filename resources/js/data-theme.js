window.__setIconTheme = function(theme) {
    let themeToggle = document.getElementById('js-theme-toggle')

    if (! themeToggle) {
        return;
    }

    themeToggle.textContent = ''

    if (theme === 'dark') {
        themeToggle.className = 'mode-dark icon solid fa-sun without-style'
    } else if (theme === 'light') {
        themeToggle.className = 'mode-light icon solid fa-moon without-style'
    }
}

document.addEventListener('DOMContentLoaded', function(event) {
    let toggleSwitch = document.getElementById('js-theme-toggle'),
        currentTheme = localStorage.getItem('theme'),
        theme = null

    if (currentTheme && document.body.getAttribute('data-theme')) {
        theme = currentTheme
    }

    if (theme) {
        __setIconTheme(theme)
    }

    toggleSwitch.addEventListener('click', (e) => {
        if (toggleSwitch.classList.contains('mode-light')) {
            __setTheme('dark')
        } else if (toggleSwitch.classList.contains('mode-dark')) {
            __setTheme('light')
        }

        e.preventDefault();     // Cancel the native event
        e.stopPropagation();    // Don't bubble/capture the event any further
    })
})
