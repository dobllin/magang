<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Test nggak butuh hasil build CSS/JS dari Vite.
        $this->withoutVite();
    }
}
