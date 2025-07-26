<?php

declare(strict_types=1);

use App\Models\Activity;

beforeEach(function () {
	$this->seed();
	$this->user('partner', 'stadium')->api();
	$this->url = '/api/partner/v1';
});

describe('Activity Controller tests', function () {
	it('returns all activities for the authenticated partner', function () {
		$this->user->badge->activities()->delete();
		Activity::factory(2)->for($this->user->badge, 'belongTo')->create();
		$res = $this->getJson("$this->url/activities/all")->assertOk();
		expect($res->json('payload.activities'))->toHaveCount(2);
	});

	it('finds a specific activity by ID', function () {
		$activity = Activity::factory()->for($this->user->badge, 'belongTo')->create();

		$res = $this->getJson("$this->url/activities/find?activity_id=$activity->id");

		$res->assertOk();
		expect($res->json('payload.activity.id'))->toBe($activity->id);
	});

	it('cant finds a specific activity by invalid ID', function () {
		$this->getJson("$this->url/activities/find?activity_id=422")->assertStatus(422);
	});

	it('cant finds a specific activity by unauthorized ID', function () {
		$this->getJson("$this->url/activities/find?activity_id=3")->assertStatus(404);
	});

	it('creates a new activity', function () {
		$data = Activity::factory()
			->for($this->user->badge, 'belongTo')
			->make(['partner_id' => $this->user->id])
			->toArray();
		$res = $this->postJson("$this->url/activities/create", $data);
		$res->assertOk();
		expect($res->json('payload.activity'))->not->toBeNull();
	});

	it('cant creates a new activity with invalid data', function () {
		$this->postJson("$this->url/activities/create", [])->assertStatus(422);
	});

	it('updates an existing activity', function () {
		$activity = Activity::factory()
			->for($this->user->badge, 'belongTo')
			->create();
		$activity->update(['name'=>'tido']);
		$data = ['activity_id'=>$activity->id,...$activity->toArray()];
		$res = $this->patchJson("$this->url/activities/update", $data);
		$res->assertOk();
		expect($res->json('payload.activity.name'))->toBe($activity->name);
	});

	it('cant updates an existing activity with invalid data', function () {
		$this->patchJson("$this->url/activities/update",[])->assertStatus(422);
	});

	it('deletes an activity', function () {
		$activity = Activity::factory()
			->for($this->user->badge, 'belongTo')
			->create();

		$res = $this->deleteJson("$this->url/activities/delete", [
			'activity_id' => $activity->id,
		]);

		$res->assertOk();
		expect(Activity::find($activity->id))->toBeNull();
	});

	it('toggles activation of an activity', function () {
		$activity = Activity::factory()
			->for($this->user->badge, 'belongTo')
			->create(['is_active' => false]);

		$res = $this->postJson("$this->url/activities/toggleActivation", [
			'activity_id' => $activity->id,
		]);

		$res->assertOk();
		$activity->refresh();
		expect($activity->is_active)->toBeTrue();
	});
});
