<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisSubscriber extends Command
{
    protected $signature = 'redis:subscribe';
    protected $description = 'Subscribe to a Redis channel';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        Redis::psubscribe(['1'],function($message){
            $this->info("Message Received: ".$message);
        });
        // Redis::subscribe(['my-channel'],function($message){
        //     $this->info("Message Received: ".$message);
        // });
    }
}
