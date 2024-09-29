<?php

pest()->extend(Tests\TestCase::class);

// Constants for age limits
const MIN_AGE = 20;
const MAX_AGE = 50;

describe('Input Age Tests', function () {

    it('should successfully access the input age page', function () {
        $this->get('/input-age')->assertStatus(200)->assertSee('halaman input umur');
    });

    it('should successfully input age between 20 to 50', function () {
        $validAge = 40;
        $response = $this->post('/input-age', ['age' => $validAge])->assertStatus(200);
        expect($response->json('message'))->toBe('Age successfully submitted!');
        expect($response->json('data.age'))->toBe($validAge);
    });

    it('should fail to input age less than 20', function () {
        $invalidAge = 15;
        $this->post('/input-age', ['age' => $invalidAge])
            ->assertStatus(302)
            ->assertSessionHasErrors('age')
            ->assertSessionHasErrors(['age' => 'The age field must be between ' . MIN_AGE . ' and ' . MAX_AGE . '.']);
    });

    it('should fail to input age more than 50', function () {
        $invalidAge = 63;
        $this->post('/input-age', ['age' => $invalidAge])
            ->assertStatus(302)
            ->assertSessionHasErrors('age')
            ->assertSessionHasErrors(['age' => 'The age field must be between ' . MIN_AGE . ' and ' . MAX_AGE . '.']);
    });

    it('should fail to input non-integer age', function () {
        $invalidAge = 25.5;
        $this->post('/input-age', ['age' => $invalidAge])
            ->assertStatus(302)
            ->assertSessionHasErrors('age')
            ->assertSessionHasErrors(['age' => 'The age field must be an integer.']);
    });

    it('should fail to input non-numeric age', function () {
        $invalidAge = 'blabla';
        $this->post('/input-age', ['age' => $invalidAge])
            ->assertStatus(302)
            ->assertSessionHasErrors('age')
            ->assertSessionHasErrors(['age' => 'The age field must be a number.']);
    });

});