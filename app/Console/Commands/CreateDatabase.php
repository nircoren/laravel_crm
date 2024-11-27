<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputArgument;

class CreateDatabase extends Command
{
    protected $name = "make:database";

    /**
     * The console command description.
     *
     * @var string
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the database'],
        ];
    }

    public function fire()
    {
        DB::getConnection()->statement('CREATE DATABASE :schema', ['schema' => $this->argument('name')]);
    }
}
