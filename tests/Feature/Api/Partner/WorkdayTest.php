<?php

declare(strict_types=1);

use App\Enums\PartnersTypes;
use App\Models\Partner;
use App\Models\Workday;

beforeEach(function () {
	$this->seed();
	$this->user('partner', 'stadium')->api();
	$this->url = '/api/partner/v1';
});

describe('Partner controller tests', function () {

	it('can fetch partner workdays', function () {
		$res = $this->getJson("$this->url/workdays/all")->assertOk();
		expect($res->json('payload.workdays'))->not->toBeNull();
	});

	it('cant fetch invalid partner workdays', function () {
		$this->user->update(['verified_at' => null]);
		$this->getJson("$this->url/workdays/all")->assertStatus(403);
	});

	it('can create partner workday', function () {
		$data = Workday::factory()->make(['slot_duration_id' => 2])->toArray();
		$res = $this->postJson("$this->url/workdays/create", $data)->assertOk();
		expect($res->json('payload.workday'))->not->toBeNull();
	});

	it('cant create partner workday with invalid data', function () {
		$data = Workday::factory()->make()->toArray();
		$this->postJson("$this->url/workdays/create", $data)->assertStatus(422);
	});

	it('cant create workday with invalid partner account', function () {
		$this->user->update(['verified_at' => null]);
		$data = Workday::factory()->make(['slot_duration_id' => 1])->toArray();
		$this->postJson("$this->url/workdays/create", $data)->assertStatus(403);
	});


	it('finds a specific workday by ID', function () {
		$day = $this->user->badge->workdays->first();
		$res = $this->getJson("$this->url/workdays/find?workday_id={$day->id}")->assertOk();
		expect($res->json('payload.workday.id'))->toBe($day->id);
	});

	it('cant finds a specific workday with invalid id', function () {
		$this->getJson("$this->url/workdays/find?workday_id=5000")->assertStatus(422);
	});

	it('cant finds a specific workday with unauthorized id', function () {
		$partner = Partner::factory()->create(['type' => PartnersTypes::stadium->name]);
		$day = Workday::factory()->for($partner, 'belongTo')->create();
		$this->getJson("$this->url/workdays/find?workday_id={$day->id}")->assertStatus(404);
	});

	it('updates an existing workday', function () {
		$day = $this->user->badge->workdays->first();
		$data = [
			'workday_id' => $day->id,
			'from' => '21',
			'to' => '00',
			'slot_duration_id' => 1,
		];
		$res = $this->patchJson("$this->url/workdays/update", $data)->assertOk();
		expect($res->json('payload.workday.from'))->toBe($data['from'])
			->and($res->json('payload.workday.to'))->toBe($data['to']);
	});

	it('cant updates an existing workday with invalid data', function () {
		$this->patchJson("$this->url/workdays/update", [])->assertStatus(422);
	});

	it('deletes a workday', function () {
		$day = $this->user->badge->workdays->first();
		$this->deleteJson("$this->url/workdays/delete", ['workday_id' => $day->id,])->assertOk();
		expect(Workday::find($day->id))->toBeNull();
	});

	it('toggles activation of a workday', function () {
		$day = $this->user->badge->workdays->first();
		$day->update(['is_active'=>0]);
		$this->postJson("$this->url/workdays/toggleActivation", [
			'workday_id' => $day->id,
		])->assertOk();
		expect($day->fresh()->is_active)->toBeTrue();
	});
});
