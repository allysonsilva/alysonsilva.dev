<?php

use App\Support\Helpers\MixLocal;
use Illuminate\Support\HtmlString;

function mix_local(string $path, string $manifestDirectory = ''): HtmlString|string
{
    return app(MixLocal::class)(...func_get_args());
}
