<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('check:items')]
#[Description('Command description')]
class CheckBorrowingItems extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
