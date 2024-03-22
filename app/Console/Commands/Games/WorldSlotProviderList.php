<?php

namespace App\Console\Commands\Games;

use App\Traits\Commands\Games\WorldslotGamesCommandTrait;
use Illuminate\Console\Command;

class WorldslotProviderList extends Command
{
    use WorldslotGamesCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'worldslot:providers-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        return self::getProvider();
    }
}
