<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Redis::del('trending_threads');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Redis::del('trending_threads');
    }


    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
        $this->assertEmpty(Redis::zrevrange('trending_threads', 0, -1));

        $thread = create(Thread::class);

        $this->get(route('threads.show', ['channel' => $thread->channel, 'thread' => $thread]));

        $trending = Redis::zrevrange('trending_threads', 0, -1);

        $this->assertCount(1, $trending);
        $this->assertEquals($thread->title, json_decode($trending[0])->title);
    }
}
