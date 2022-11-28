// Menu Mobile
;(function() {
    /* In animations (to close icon) */
    const beginAC = 80, endAC = 320, beginB = 80, endB = 320

    function inAC(s) {
        s.draw('80% - 240', '80%', 0.3, {
            delay: 0.1,
            callback: function() {
                inAC2(s)
            },
        })
    }

    function inAC2(s) {
        s.draw('100% - 545', '100% - 305', 0.6, {
            easing: ease.ease('elastic-out', 1, 0.3),
        })
    }

    function inB(s) {
        s.draw(beginB - 60, endB + 60, 0.1, {
            callback: function() {
                inB2(s)
            },
        })
    }

    function inB2(s) {
        s.draw(beginB + 120, endB - 120, 0.3, {
            easing: ease.ease('bounce-out', 1, 0.3),
        })
    }

    /* Out animations (to burger icon) */
    function outAC(s) {
        s.draw('90% - 240', '90%', 0.1, {
            easing: ease.ease('elastic-in', 1, 0.3),
            callback: function() {
                outAC2(s)
            },
        })
    }

    function outAC2(s) {
        s.draw('20% - 240', '20%', 0.3, {
            callback: function() {
                outAC3(s)
            },
        })
    }

    function outAC3(s) {
        s.draw(beginAC, endAC, 0.7, {
            easing: ease.ease('elastic-out', 1, 0.3),
        })
    }

    function outB(s) {
        s.draw(beginB, endB, 0.7, {
            delay: 0.1,
            easing: ease.ease('elastic-out', 2, 0.4),
        })
    }

    /* Awesome burger default */
    const   pathA = document.getElementById('pathA'),
            pathB = document.getElementById('pathB'),
            pathC = document.getElementById('pathC'),
            segmentA = new Segment(pathA, beginAC, endAC),
            segmentB = new Segment(pathB, beginB, endB),
            segmentC = new Segment(pathC, beginAC, endAC)

    let menuIconTrigger = document.getElementById('menu-icon-trigger'),
        toCloseIcon = true,
        menuMobile = document.getElementById('menu-mobile'),
        menuToggleItem = document.getElementById('menu-toggle-item')

    menuToggleItem.style.visibility = 'visible'

    menuIconTrigger.onclick = function() {
        window.scrollTo(0, 0);

        if (toCloseIcon) {

            menuMobile.classList.toggle('show-menu-mobile')

            inAC(segmentA)
            inB(segmentB)
            inAC(segmentC)

            setTimeout(() => {
                menuMobile.classList.toggle('menu-mobile--active')
            }, 150)

        } else {

            outAC(segmentA)
            outB(segmentB)
            outAC(segmentC)

            menuMobile.classList.toggle('menu-mobile--active')

            setTimeout(() => {
                menuMobile.classList.toggle('show-menu-mobile')
            }, 750)

        }

        toCloseIcon = ! toCloseIcon
    }
})()

// Search
let itemSearch = document.querySelector('.search-link-item'),
    searchOverlay = document.getElementById('search-overlay'),
    searchCloseButton = document.querySelector('.search-close'),
    searchFormContainer = document.querySelector('.search-form-container');

itemSearch.onclick = (event) => {
    event.preventDefault();

    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Fallback to {ScrollToOptions} parameter
    ;(function smoothscroll() {
        let currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
        if (currentScroll > 0) {
            window.requestAnimationFrame(smoothscroll);
            window.scrollTo (0, currentScroll - (currentScroll / 5));
        }
    })();

    searchFormContainer.style.display = "none";

    searchOverlay.classList.toggle('search-overlay-hidden');
    searchOverlay.classList.toggle('search-overlay-visible');

    setTimeout(() => {
        searchOverlay.classList.remove('search-overlay', 'search-overlay-visible');
        searchOverlay.classList.add('search-form', 'search-form-visible');

        searchFormContainer.removeAttribute('style');

        setTimeout(() => {
            document.getElementById('search-form-input').focus();
        }, 100);
    }, 300);
};

searchCloseButton.onclick = (event) => {
    event.preventDefault();

    searchFormContainer.style.display = "none";

    searchOverlay.classList.add('search-overlay', 'search-overlay-visible-without-animation');
    searchOverlay.classList.remove('search-form', 'search-form-visible');

    setTimeout(() => {
        searchOverlay.classList.remove('search-overlay-visible-without-animation');

        searchOverlay.classList.toggle('search-overlay-hidden');
    }, 100);
};
