<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Location;
use App\Models\Partner;

final class LocationServices
{
    public function get(Partner $partner)
    {
        Required($partner, 'partner');
        $location = $partner->badge->location;
        NotFound($location);

        return $location;
    }

    public function create(Partner $partner, array $data): Location
    {
        Required($data, 'data');
        Truthy(! method_exists($partner->badge, 'location'), 'missing location method');
        $data = $this->checkAndCastData($data, [
            'long' => 'float',
            'lat' => 'float',
            'name' => 'string|name',
        ]);

        return $partner->badge->location()->create([
            'long' => round($data['long'], 8),
            'lat' => round($data['lat'], 8),
            'name' => $data['name'],
        ]);
    }

    public function update(Partner $partner, array $data): bool
    {
        $data = $this->checkAndCastData($data, [
            'long' => 'float',
            'lat' => 'float',
            'name' => 'string|name',
        ]);

        return $partner->badge->location->update([
            'long' => round($data['long'], 8),
            'lat' => round($data['lat'], 8),
            'name' => $data['name'],
        ]);
    }

    public function delete(Partner $partner): int
    {
        Truthy($partner->badge->location === null, 'location not set');

        return $partner->badge->location()->delete();
    }

    public function checkLocationExists(Partner $partner): bool
    {
        return $partner->badge->location !== null;
    }

    private function checkAndCastData(array $data, $requiredFields = []): array
    {
        Truthy(empty($data), 'data is empty');
        if (empty($requiredFields)) {
            return $data;
        }
        $missing = [];
        foreach ($requiredFields as $key => $value) {
            if (str_contains($value, '|')) {
                [$type, $default] = explode('|', $value);
                $value = $type;
                if (! isset($data[$key])) {
                    $data[$key] = $default;
                }
            }

            if (str_contains($key, '.')) {
                [$name, $sub] = explode('.', $key);
                if (! isset($data[$name][$sub])) {
                    $missing[] = $key;

                    continue;
                }
                settype($data[$name][$sub], $value);

                continue;
            }
            if (! isset($data[$key])) {
                $missing[] = $key;

                continue;
            }
            settype($data[$key], $value);
        }
        Falsy(empty($missing), 'fields missing: '.implode(', ', $missing));

        return $data;
    }
}
