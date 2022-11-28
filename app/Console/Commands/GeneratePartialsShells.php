<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GeneratePartialsShells extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-partials-shell';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gerar as partes do shell(partial-header.html + partial-footer.html)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var string */
        $shellPublicFolder = $this->ask('Pasta para salvar os arquivos?', public_path('app-shell'));

        File::put("{$shellPublicFolder}/partial-header.html", view('app-shell.partial-header')->render());
        File::put("{$shellPublicFolder}/partial-footer.html", view('app-shell.partial-footer')->render());

        $this->info('Partes do shell {partial-header.html} && {partial-footer.html} gerados com sucesso ✅');
    }
}
