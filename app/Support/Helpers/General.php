<?php

use Illuminate\Support\HtmlString;

function mix_local(string $path): HtmlString|string
{
    config()->set('app.mix_url', null);

    return mix($path);
}
