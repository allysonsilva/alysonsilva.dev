<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use function Termwind\{render};
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\Console\Isolatable;

class MakeUserAuthLink extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auth-link {user=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an authentication link for the admin user.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $adminUser = User::findOrFail($this->argument('user'));

        $url = URL::temporarySignedRoute(
            'web.signed-login', now()->addMinutes(10), ['admin' => $adminUser->getKey()]
        );

        render(<<<HTML
            <div class="text-rose-500">
                <br/>
                Access the URL to login: <a href="{$url}" class="text-sky-400">$url</a>
                <br/>
            </div>
        HTML);
    }
}
