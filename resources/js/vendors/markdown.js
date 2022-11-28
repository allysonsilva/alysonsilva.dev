import MarkdownIt from 'markdown-it'

Prism.plugins.NormalizeWhitespace.setDefaults({
    'remove-trailing': true,
    'remove-indent': true,
    'left-trim': true,
    'right-trim': true,
    'tabs-to-spaces': 4,
})

// Example:
//
// import md from '@vendors/markdown'
// import DOMPurify from 'dompurify'
//
// let markdownContent = document.getElementById('markdown-content')
// let html = md.render(markdownContent.innerHTML)
// markdownContent.innerHTML = ''
// let cleanHTML = DOMPurify.sanitize(html)
// markdownContent.innerHTML = cleanHTML
// Prism.highlightAll()

const markdown = new MarkdownIt('default', {
    html: true,
    linkify: true,
    typographer: true,
    highlight(code, lang) {
        let html
        lang = lang.trim()

        if (! lang) return;

        try {
            html = Prism.highlight(code, Prism.languages[lang])
        } catch (error) {
            console.error(error)

            throw new Error(`Cannot load Prism languages "${lang}". Please check the spelling.`)
        }

        html = markdown.utils.escapeHtml(code)

        return `<pre class="line-numbers language-${lang}"><code class="language-${lang}">${html}</code></pre>`
    },
})

export default window.markdown = window.markdownIt = markdown
