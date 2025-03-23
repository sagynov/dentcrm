<?php

use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTruncation;

uses(DatabaseTruncation::class);

test('guest can register', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/register')
                ->waitForText('Create an account')
                ->assertSee('Create an account')
                ->screenshot('success-register');
    });
});
