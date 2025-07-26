<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use App\Services\MediaServices;
use App\Validators\MediaValidators;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class MediasController extends Controller
{
    public function __construct(public MediaServices $services) {}

    public function all()
    {
        $medias = $this->services->allByPartner(Auth::user());

        return Success(payload: [
            'medias' => MediaResource::collection($medias),
        ]);
    }

    public function find(Request $request)
    {
        $validator = MediaValidators::find($request->all());
        $media = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('media_id')
        );

        return Success(payload: [
            'media' => MediaResource::make($media),
        ]);
    }

    public function findByName(Request $request)
    {
        $validator = MediaValidators::findByName($request->all());
        $media = $this->services->findByPartnerByName(
            Auth::user(),
            $validator->safe()->string('media_name')->toString()
        );

        return Success(payload: [
            'media' => MediaResource::make($media),
        ]);
    }

    public function findByGroup(Request $request)
    {
        $validator = MediaValidators::findByGroup($request->all());
        $media = $this->services->findByPartnerByGroup(
            Auth::user(),
            $validator->safe()->string('media_group')->toString()
        );

        return Success(payload: [
            'media' => MediaResource::collection($media),
        ]);
    }

    public function create(Request $request)
    {
        $validator = MediaValidators::create($request->all());
        $media = $this->services->create(
            Auth::user(),
            $validator->safe()->all()
        );

        return Success(payload: [
            'media' => MediaResource::collection($media->fresh()),
        ]);
    }

    public function update(Request $request)
    {
        $validator = MediaValidators::update($request->all(), true);
        $media = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('media_id')
        );

        $this->services->update(
            Auth::user(),
            $media,
            $validator->safe()->except('media_id')
        );

        return Success(payload: [
            'media' => MediaResource::make($media->fresh()),
        ]);
    }

    public function delete(Request $request)
    {
        $validator = MediaValidators::find($request->all());
        $media = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('media_id')
        );
        $this->services->delete($media);

        return Success(msg: 'media deleted');
    }

    public function deleteByGroup(Request $request)
    {
        $validator = MediaValidators::findByGroup($request->all());
        $media = $this->services->findByPartnerByGroup(
            Auth::user(),
            $validator->safe()->string('media_group')->toString()
        );
        $this->services->deleteByGroup($media);

        return Success(msg: 'media deleted');
    }
}
