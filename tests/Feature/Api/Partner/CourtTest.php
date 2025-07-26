<?php

declare(strict_types=1);

use App\Models\Court;

beforeEach(function () {
	$this->seed();
	$this->user('partner', 'stadium')->api();
	$this->url = '/api/partner/v1';
});

describe('Court controller tests', function () {
	it('can get courts for partner', function () {
		$res = $this->getJson("$this->url/courts/all");
		expect($res->status())->toBe(200)
			->and($res->json('payload.courts'))->not->toBeNull();
	});

	it('cant get courts for invalid partner', function () {
		$this->user->update(['verified_at' => null]);
		$res = $this->getJson("$this->url/courts/all");
		expect($res->status())->toBe(403);
	});

	it('cant get courts for trainer partner', function () {
		$this->user->update(['type' => 'trainer']);
		$res = $this->getJson("$this->url/courts/all");
		expect($res->status())->toBe(403);
	});

	it('can find court by id for partner', function () {
		$court = $this->user->badge;
		$res = $this->getJson("$this->url/courts/find?court_id={$court->id}");
		expect($res->status())->toBe(200)
			->and($res->json('payload.court'))->not->toBeNull()
			->and($res->json('payload.court.name'))->toBe($court->name);
	});

	it('cant get court by id for unauthoerd partner', function () {
		$court = $this->user->badge;
		$court->update(['partner_id' => 10]);
		$res = $this->getJson("$this->url/courts/find?court_id={$court->id}");
		expect($res->status())->toBe(404);
	});

	it('can create court for partner', function () {
		$data = Court::factory()->make()->toArray();
		$res = $this->postJson("$this->url/courts/create", $data);
		expect($res->status())->toBe(200)
			->and($res->json('payload.court.name'))->toBe($data['name']);
	});

	it('can set the first court for partner as main',function(){
		$this->user->courts()->delete();
		expect($this->user->courts()->count())->toBe(0);
		$court = Court::factory()->make()->toArray();
		$res = $this->postJson("$this->url/courts/create", $court);
		expect($res->status())->toBe(200)
			->and($res->json('payload.court.is_main'))->toBeTrue();
	});

	it('cant create court for invalid partner', function () {
		$this->user->update(['verified_at' => null]);
		$data = Court::factory()->make()->toArray();
		$res = $this->postJson("$this->url/courts/create", $data);
		expect($res->status())->toBe(403);
	});

	it('cant create court for trainer partner', function () {
		$this->user->update(['type' => 'trainer']);
		$data = Court::factory()->make()->toArray();
		$res = $this->postJson("$this->url/courts/create", $data);
		expect($res->status())->toBe(403);
	});

	it('can update court for partner', function () {
		$court = $this->user->badge;
		$data = [
			'court_id'=>$court->id,
			'name'=>'XXXXXX',
		];
		$res = $this->patchJson("$this->url/courts/update", $data);
		expect($res->status())->toBe(200)
			->and($res->json('payload.court.name'))->toBe($data['name']);
	});

	it('cant update court for unauthored partner', function () {
		$court = $this->user->badge;
		$court->update(['partner_id'=>10]);
		$data = [
			'court_id'=>$court->id,
			'name'=>'XXXXXX',
		];
		$res = $this->patchJson("$this->url/courts/update", $data);
		expect($res->status())->toBe(404);
	});

	it('cant delete court for partner if court is main', function () {
		$court = $this->user->badge;
		$res = $this->deleteJson("$this->url/courts/delete?court_id={$court->id}");
		expect($res->status())->toBe(400);
		$this->assertDatabaseHas('courts',['id'=>$court->id]);
	});

	it('can delete court for partner if not main', function () {
		$court = $this->user->badge;
		$court->update(['is_main'=>0]);
		$res = $this->deleteJson("$this->url/courts/delete?court_id={$court->id}");
		expect($res->status())->toBe(200);
		$this->assertDatabaseMissing('courts',['id'=>$court->id]);
	});

	it('can set not main court as main', function () {
		$court = $this->user->badge;
		expect($court->is_main)->toBeTrue();
		$court->update(['is_main'=>0]);
		expect($court->fresh()->is_main)->toBeFalse();
		$res = $this->postJson("$this->url/courts/setMain",['court_id'=>$court->id]);
		expect($res->status())->toBe(200)
		->and($court->fresh()->is_main)->toBeTrue();
	});

	it('can toggle court activation status', function () {
		$court = $this->user->badge;
		$court->update(['is_active'=>0]);
		expect($court->fresh()->is_active)->toBeFalse();
		$res = $this->postJson("$this->url/courts/toggleActivation",['court_id'=>$court->id]);
		expect($res->status())->toBe(200)
		->and($court->fresh()->is_active)->toBeTrue();
	});
});
