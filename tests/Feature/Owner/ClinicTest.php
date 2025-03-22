<?php
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('owners clinic list displayed', function () {
    $user = User::factory()->create();
    $response = $this
        ->actingAs($user)->get(route('owner.clinics.index'));

    $response->assertOk();
});

// test('owner can add a new clinic', function () {
//     $user = User::factory()->create();
//     $response = $this->actingAs($user)->post(route('owner.clinics.store'), [
//         'name' => fake()->word(),

//     ]);
// });