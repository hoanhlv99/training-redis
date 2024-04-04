<?php

namespace App\Console\Commands;

use App\Model\SbUserViewTmp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class SysDataToRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sysdata:redis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sys Data To Redis';

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
     * @return int
     */
    public function handle()
    {
        try {
            Log::info('start sys data to redis');

            $data = SbUserViewTmp::query()->get();
            $redis = Redis::connection();
            foreach ($data as $value) {
                $this->sysUserInfo($redis, $value);

                $this->sysListUserViewProduct($redis, $value);

                $this->sysHistoryAccess($redis, $value);
            }
            Log::info('end sys data to redis');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
        }
    }

    private function sysUserInfo($redis, $data)
    {
        $key = 'imei:' . $data->imei;
        $redis->hmset($key, 'ip', $data->ip, 'user_agent', $data->user_agent );
    }

    private function sysListUserViewProduct($redis, $data)
    {
        $key = 'product_access:' . $data->target;
        $time = strtotime($data->created_at);
        $redis->zadd($key, $time, $data->imei);
    }

    private function sysHistoryAccess($redis, $data)
    {
        $key= 'user_access_history:' . $data->imei;
        $time = strtotime($data->created_at);
        $redis->zadd($key, $time, $data->target);
    }
}
