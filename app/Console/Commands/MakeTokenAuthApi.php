<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use function Termwind\{render};
use Illuminate\Contracts\Console\Isolatable;

class MakeTokenAuthApi extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auth-api-token
                        {name : Token name for reference}
                        {user=1 : User ID to which the token belongs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a sanctum authentication token to be used in the api.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $adminUser = User::findOrFail($this->argument('user'));

        // Revoke all tokens...
        $adminUser->tokens()->delete();

        $token = $adminUser->createToken($this->argument('name'));

        render(<<<HTML
            <div class="text-sky-400">
                <br/>
                The token should be included in the <strong class="text-gray-100">"Authorization"</strong> header as a <strong class="text-gray-100">"Bearer"</strong> token:
                <br/>
                <br/>
                <em class="px-1 text-rose-500">$token->plainTextToken</em>
            </div>
        HTML);
    }
}
