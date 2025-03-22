<?php

use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTruncation;

uses(DatabaseTruncation::class);

test('guest can register', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/register')
                ->assertSee('Create an account');
    });
});
