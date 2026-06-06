<?php

use App\Models\User;

test('o ecrã de confirmação de palavra-passe pode ser renderizado', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('password.confirm'));

    $response->assertOk();
});