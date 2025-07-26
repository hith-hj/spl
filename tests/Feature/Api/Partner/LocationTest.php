<?php

declare(strict_types=1);

use App\Models\Partner;
use App\Models\Location;

beforeEach(function () {
	$this->seed();
	$this->user('partner', 'stadium')->api();
	$this->url = '/api/partner/v1';
	Location::factory()->for($this->user->badge,'belongTo')->create();
});

describe('Location controller tests', function () {

	it('can fetch partner location', function () {
		$res = $this->getJson("$this->url/locations/get");
		$res->assertOk();
		expect($res->json('payload.locations'))->not->toBeNull();
	});

	it('cant fetch invalid partner location', function () {
		$this->user->update(['verified_at' => null]);
		$res= $this->getJson("$this->url/locations/get");
		$res->assertStatus(403);
	});

	it('can create partner location', function () {
		$this->user->badge->location->delete();
		$data = Location::factory()->make()->toArray();
		$res = $this->postJson("$this->url/locations/create", $data)->assertOk();
		expect($res->json('payload.location'))->not->toBeNull();
	});

	it('cant create partner location when location exists', function () {
		$this->postJson("$this->url/locations/create", [])->assertStatus(400);
	});

	it('cant create partner location with invalid data', function () {
		$this->user->badge->location->delete();
		$res = $this->postJson("$this->url/locations/create", []);
		$res->assertStatus(422);
	});

	it('cant create location with invalid partner account', function () {
		$this->user->update(['verified_at' => null]);
		$data = Location::factory()->make()->toArray();
		$this->postJson("$this->url/locations/create", $data)->assertStatus(403);
	});

	it('updates an existing location', function () {
		$location = $this->user->badge->location;
		$data = [
			'location_id' => $location->id,
			'long' => 31.111,
			'lat' => 31.111,
		];
		$res = $this->patchJson("$this->url/locations/update", $data);
		$res->assertOk();
		expect($location->fresh()->long )->toBe($data['long'])
			->and($location->fresh()->lat )->toBe($data['lat']);
	});

	it('cant updates an existing location with invalid data', function () {
		$this->patchJson("$this->url/locations/update", [])->assertStatus(422);
	});

	it('deletes a location', function () {
		$location = $this->user->badge->location;
		$this->deleteJson("$this->url/locations/delete", ['location_id' => $location->id,])
		->assertOk();
		expect(Location::find($location->id))->toBeNull();
	});


});
