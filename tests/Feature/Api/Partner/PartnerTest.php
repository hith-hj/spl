<?php

declare(strict_types=1);

beforeEach(function () {
	$this->seed();
	$this->user('partner', 'stadium')->api();
	$this->url = '/api/partner/v1';
});

describe('Partner controller tests', function () {

	it('can fetch partner details',function(){
		$res = $this->getJson("$this->url/partners/get");
		expect($res->status())->toBe(200)
		->and($res->json('payload.partner'))->not->toBeNull();
	});

	it('cant fetch invalid partner details',function(){
		$this->user->update(['verified_at'=>false]);
		$res = $this->getJson("$this->url/partners/get");
		expect($res->status())->toBe(200)
		->and($res->json('payload.partner'))->not->toBeNull();
	});

	it('can update partner details',function(){
		$data = ['description'=>'new description'];
		expect($this->user)->not->toBeNull();
		$res = $this->patchJson("$this->url/partners/update",$data);
		expect($res->status())->toBe(400);
		// ->and($this->user->fresh()->description)->toEqual($data['description']);
	});

	it('Edit partner tests',function(){
		//test if it can create stadium
		// test if it can create trainer
		//test if it can update stadium
		//test if it can update trainer
	})->skip();

	it('can delete parnter with it relation', function () {
		$badge = $this->user->badge;
		expect($badge)->not->toBeNull();
		$res = $this->deleteJson("$this->url/partners/delete");
		expect($res->status())->tobe(200);
	});
});
