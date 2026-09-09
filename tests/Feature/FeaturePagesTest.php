<?php

namespace Tests\Feature;

use Tests\TestCase;

class FeaturePagesTest extends TestCase
{
    public function test_feature_pages_load_successfully(): void
    {
        foreach (['/pantry', '/planner', '/assistant', '/community', '/admin'] as $path) {
            $response = $this->get($path);
            $response->assertOk();
        }
    }
}
