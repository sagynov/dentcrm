<?php

use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\Browser\Pages\Login;

uses(DatabaseTruncation::class);

test('user can log in', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser)use($user) {
        $browser->visit(new Login)
            ->waitForText('Phone number')
            ->type('@phone', $user->phone)
            ->type('@password', 'password')
            ->press('Log in')
            ->waitForText('Dashboard')
            ->assertPathIs('/dashboard');
        $browser->screenshot('after-login');
    });
});
