<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Redis;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function setRedis($key, $json)
    {
        $redis = new Redis();
        try {
            $json = json_encode($json);
            $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
            $redis->set($key, $json, ['EX' => 60]);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function getRedis($key)
    {
        $redis = new Redis();
        try {
            $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
            $json = $redis->get($key);
            if ($json) {
                return json_decode($json);
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteRedis($key)
    {
        $redis = new Redis();
        try {
            $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
        } catch (\Exception $e) {
            return false;
        }

        return $redis->del($key);
    }
}
