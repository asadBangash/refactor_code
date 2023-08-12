<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Helpers\TestHelper;
use Carbon\Carbon;

class WillExpireAtTest extends TestCase
{
    public function testWillExpireAtWithDueTimeBefore90Hours()
    {
        // Arrange
        $due_time = Carbon::now()->addHours(50)->toDateTimeString();
        $created_at = Carbon::now()->toDateTimeString();

        // Act
        $result = TestHelper::willExpireAt($due_time, $created_at);

        // Assert
        $this->assertEquals($due_time, $result);
    }

    public function testWillExpireAtWithDueTimeWithin24Hours()
    {
        // Arrange
        $due_time = Carbon::now()->addHours(12)->toDateTimeString();
        $created_at = Carbon::now()->toDateTimeString();
        $expected_time = Carbon::parse($created_at)->addMinutes(90)->toDateTimeString();

        // Act
        $result = TestHelper::willExpireAt($due_time, $created_at);

        // Assert
        $this->assertEquals($expected_time, $result);
    }

    public function testWillExpireAtWithDueTimeWithin72Hours()
    {
        // Arrange
        $due_time = Carbon::now()->addHours(60)->toDateTimeString();
        $created_at = Carbon::now()->toDateTimeString();
        $expected_time = Carbon::parse($created_at)->addHours(16)->toDateTimeString();

        // Act
        $result = TestHelper::willExpireAt($due_time, $created_at);

        // Assert
        $this->assertEquals($expected_time, $result);
    }

    public function testWillExpireAtWithDueTimeAfter72Hours()
    {
        // Arrange
        $due_time = Carbon::now()->addHours(100)->toDateTimeString();
        $created_at = Carbon::now()->toDateTimeString();
        $expected_time = Carbon::parse($due_time)->subHours(48)->toDateTimeString();

        // Act
        $result = TestHelper::willExpireAt($due_time, $created_at);

        // Assert
        $this->assertEquals($expected_time, $result);
    }
}
