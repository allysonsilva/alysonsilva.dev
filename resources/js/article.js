import AnchorJS from 'anchor-js'
import md from '@vendors/markdown'
import DOMPurify from 'dompurify'

document.addEventListener('DOMContentLoaded', function(event) {
    let like = document.querySelector('.btn.like');
    let counter = like.querySelector('.counter');

    const setCounter = function (count) {
        counter.innerHTML = `${count}`;
    };

    const addLike = function (evt) {
        let postSlug = this.dataset.slug.trim();

        axios.post(`/blog/${postSlug}/like`).then (response => {
            counter.hidden = false;

            setCounter(response.data);

            this.value = 'liked';
            this.classList.add('primary');
            this.classList.remove('light');
        });

        evt.preventDefault();
        evt.stopPropagation();
    };

    const removeLike = function (evt) {
        let postSlug = this.dataset.slug.trim();

        axios.delete(`/blog/${postSlug}/like`).then (response => {
            counter.hidden = true;

            setCounter(parseInt(counter.innerHTML) - 1);

            this.value = 'unliked';
            this.classList.remove('primary');
            this.classList.add('light');
        });

        evt.preventDefault();
        evt.stopPropagation();
    };

    like.addEventListener('click', function (evt) {
        let value = this.value.toLowerCase().trim();

        if (value === 'unliked') {
            addLike.bind(this)(evt);
        } else if (value === 'liked') {
            removeLike.bind(this)(evt);
        } else {
            throw 'Invalid value';
        }
    });

    ;(function() {
        let blogComments = document.querySelector('.blog-post-comments');
        let script = document.createElement('script');

        script.src='https://utteranc.es/client.js'
        script.setAttribute('repo', 'allysonsilva/blog-posts');
        script.setAttribute('issue-term', 'pathname');
        script.setAttribute('label', 'blog-comments');
        script.setAttribute('theme', `github-${window.__theme}`);
        blogComments.appendChild(script);
    })();
});

window.addEventListener('message', (event) => {
    if (event.origin !== 'https://utteranc.es') {
        return;
    }

    window.__onThemeChange = function(theme) {
        const message = {
            type: 'set-theme',
            theme: `github-${theme}`,
        };

        let utterancesIframe = document.querySelector('iframe.utterances-frame');
        utterancesIframe.contentWindow.postMessage(message, 'https://utteranc.es');
    }
});

setTimeout(() => {
    const anchors = new AnchorJS({
        class: ' without-style without-heading-link-style anchor-link',
        icon: '#',
        placement: 'right',
    })

    anchors.add(`
        h2:not(.no-anchor),
        h3:not(.no-anchor),
        h4:not(.no-anchor),
        h5:not(.no-anchor),
        h6:not(.no-anchor)`);
});
