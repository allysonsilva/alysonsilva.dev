<?php

use App\Support\Helpers\MixLocal;
use Illuminate\Support\HtmlString;

/**
 * @param string $path
 * @param string $manifestDirectory
 *
 * @return \Illuminate\Support\HtmlString|string
 */
function mix_local(string $path, string $manifestDirectory = ''): HtmlString|string
{
    return app(MixLocal::class)(...func_get_args());
}
