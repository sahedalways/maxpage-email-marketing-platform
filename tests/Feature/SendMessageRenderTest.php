<?php

namespace Tests\Feature;

use App\Http\Livewire\Admin\MessageHub\SendMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SendMessageRenderTest extends TestCase
{
    public function test_all_audience_modes_render()
    {
        $user = User::first();
        $this->actingAs($user);

        foreach (['all', 'type', 'single', 'group'] as $audience) {
            Livewire::test(SendMessage::class)
                ->set('selectedAudience', $audience)
                ->assertSee('Send');
        }
        $this->assertTrue(true);
    }
}
