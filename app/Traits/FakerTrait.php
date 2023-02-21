<?php

namespace App\Traits;

use Faker\Factory as Faker;

trait FakerTrait
{
    public function fake()
    {
        $faker = Faker::create();
        return $faker;
    }
}
