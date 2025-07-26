<?php

namespace Tests;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    public $user = null;

    public function api()
    {
        if ($this->user === null ) {
            throw new \Exception("Test User Is Null");
        }
        $token = JWTAuth::fromUser($this->user);
        $this->withHeaders(['Authorization' => "Bearer $token"]);

        return $this;
    }

    private function partner($type = 'stadium')
    {
        if (!in_array($type,['stadium','trainer'])) {
            throw new \Exception("partner $type is not valid type ");
        }
        $this->user = Partner::factory()->create(['type' => $type]);
        return $this;
    }

    private function customer()
    {
        $this->user = User::factory()->create();
        return $this;
    }

    public function user($type =null,$sub = null)
    {
        match ($type) {
            'partner' => $this->partner($sub),
            'customer' => $this->customer(),
            default => throw new \Exception("$type is not supported"),
        };
        return $this;
    }

    public function replaceUser($user)
    {
        $this->user = $user;
        return $this->api();
    }
}
