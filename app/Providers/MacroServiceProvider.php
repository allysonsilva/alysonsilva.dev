<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        /**
         * php explode() function.
         *
         * Usage: @explode($delimiter, $string)
         */
        Blade::directive('explode', function ($argumentString) {
            list($delimiter, $string) = $this->getArguments($argumentString);

            return "<?php echo explode({$delimiter}, {$string}); ?>";
        });

        /**
         * Str::readDuration($value)
         */
        Str::macro('readDuration', function(string ...$texts) {
            $totalWords = str_word_count(implode(" ", $texts));
            $minutesToRead = round($totalWords / 150);

            return (int) max(1, $minutesToRead);
        });
    }

    /**
     * Get argument array from argument string.
     *
     * @param string $argumentString
     *
     * @return array<string>
     */
    private function getArguments(string $argumentString): array
    {
        return explode(', ', str_replace(['(', ')'], '', $argumentString));
    }
}
