<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Mail\Markdown as MarkdownParser;

// @see https://darkghosthunter.medium.com/laravel-there-is-a-markdown-parser-and-you-dont-know-it-5f523e22121e
// @see https://darkghosthunter.medium.com/laravel-using-the-new-markdown-parser-the-easy-way-5ca306d854f6
class Markdown extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public string $content,)
    {
        //
    }

    public function parseMarkdown(string $markdown): string
    {
        $html = MarkdownParser::parse($markdown)->toHtml();

        // html without surrounding <p></p> tags
        // Remove wraping <p></p> tags
        return mb_ereg_replace('^<p>|</p>$', '', $html);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return <<<'blade'
            <div>
                {!! $parseMarkdown($content) !!}
            </div>
        blade;

        // return view('components.markdown');
    }
}
