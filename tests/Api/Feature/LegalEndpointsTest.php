<?php

declare(strict_types=1);

use Shopper\Core\Models\Legal;

uses(Tests\Api\TestCase::class);

it('lists only the enabled legal pages', function (): void {
    Legal::factory()->create(['title' => 'Cookie notice', 'slug' => 'cookie-notice']);
    Legal::factory()->disabled()->create(['title' => 'Draft policy', 'slug' => 'draft-policy']);

    $slugs = collect($this->getJson('/store/legals')->assertOk()->json('data'))
        ->pluck('attributes.slug');

    expect($slugs)->toContain('cookie-notice')
        ->and($slugs)->not->toContain('draft-policy');
});

it('retrieves a legal page by slug with its last update date', function (): void {
    $legal = Legal::factory()->create([
        'title' => 'Cookie notice',
        'slug' => 'cookie-notice',
        'content' => '<p>How we handle your data.</p>',
    ]);

    $this->getJson('/store/legals/cookie-notice')
        ->assertOk()
        ->assertJsonPath('data.type', 'legals')
        ->assertJsonPath('data.attributes.title', 'Cookie notice')
        ->assertJsonPath('data.attributes.content', '<p>How we handle your data.</p>')
        ->assertJsonPath('data.attributes.updated_at', $legal->updated_at->toIso8601String());
});

it('never serves a disabled or unknown legal page', function (): void {
    Legal::factory()->disabled()->create(['title' => 'Cookie notice', 'slug' => 'cookie-notice']);

    $this->getJson('/store/legals/cookie-notice')->assertNotFound();
    $this->getJson('/store/legals/missing')->assertNotFound();
});
