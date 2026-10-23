<?php

namespace Tests\Feature;

use App\Models\Tournament;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BracketFreeIsolationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_two_different_organizers_get_separate_isolated_free_tournaments(): void
    {
        // Organizer A visits /bracket/free
        $respA = $this->get('/bracket/free');
        $respA->assertRedirect();
        $targetA = $respA->headers->get('Location');
        preg_match('#/bracket/free/([^/?]+)#', $targetA, $matchesA);
        $slugA = $matchesA[1] ?? null;
        $this->assertNotNull($slugA);
        $this->assertStringStartsWith('rok-free-', $slugA);

        // Organizer B visits /bracket/free with ?new=1
        $respB = $this->get('/bracket/free?new=1');
        $respB->assertRedirect();
        $targetB = $respB->headers->get('Location');
        preg_match('#/bracket/free/([^/?]+)#', $targetB, $matchesB);
        $slugB = $matchesB[1] ?? null;
        $this->assertNotNull($slugB);
        $this->assertStringStartsWith('rok-free-', $slugB);

        // Verify slugs are distinct and isolated
        $this->assertNotEquals($slugA, $slugB);

        // Verify tournaments exist as separate records in DB
        $tourneyA = Tournament::where('slug', $slugA)->first();
        $tourneyB = Tournament::where('slug', $slugB)->first();
        $this->assertNotNull($tourneyA);
        $this->assertNotNull($tourneyB);
        $this->assertNotEquals($tourneyA->id, $tourneyB->id);

        // Captain claims slot in Tournament A
        $claimResp = $this->postJson(route('api.bracket.claim-slot', ['slug' => $slugA]), [
            'slot_index' => 0,
            'team_name' => 'Tim Petarung Alpha',
            'whatsapp' => '081234567890',
            'is_free' => 1,
        ]);
        $claimResp->assertOk();

        // Tournament A should reflect the claimed team
        $tourneyA->refresh();
        $teamsA = $tourneyA->bracket_state['teams'];
        $this->assertEquals('Tim Petarung Alpha', $teamsA[0]['name']);
        $this->assertTrue(!empty($teamsA[0]['is_claimed']));

        // Tournament B must NOT be affected (slot 0 remains unclaimed)
        $tourneyB->refresh();
        $teamsB = $tourneyB->bracket_state['teams'];
        $this->assertEquals('Nama Tim 1', $teamsB[0]['name']);
        $this->assertTrue(empty($teamsB[0]['is_claimed']));
    }
}
