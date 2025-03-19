<?php

namespace App\Support\Helpers;

use Exception;
use Illuminate\Support\HtmlString;

final class MixLocal
{
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    public function __invoke(string $path, string $manifestDirectory = ''): HtmlString|string
    {
        static $manifests = [];

        if (! str_starts_with($path, '/')) {
            $path = "/{$path}";
        }

        if ($manifestDirectory && ! str_starts_with($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        $manifestPath = public_path($manifestDirectory.'/mix-manifest.json');

        if (! isset($manifests[$manifestPath])) {
            if (! is_file($manifestPath)) {
                throw new Exception("Mix manifest not found at: {$manifestPath}");
            }

            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }

        $manifest = $manifests[$manifestPath];

        if (! isset($manifest[$path])) {
            $exception = new Exception("Unable to locate Mix file: {$path}.");

            if (! app('config')->get('app.debug')) {
                report($exception);

                return $path;
            } else {
                throw $exception;
            }
        }

        return new HtmlString($manifestDirectory.$manifest[$path]);
    }
}
