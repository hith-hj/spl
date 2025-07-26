<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PartnersTypes;
use App\Models\Course;
use App\Models\Partner;
use Exception;
use Illuminate\Support\Collection;

use function PHPUnit\Framework\throwException;

final class CourseServices
{
    private $itemsCount = 25;

    public function allByPartner(object $partner): Collection
    {
        Required($partner, 'partner');
        $courses = $partner->courses()->get(['id', 'name', 'is_main', 'is_active']);
        NotFound($courses, 'courses');

        return $courses;
    }

    public function mainByPartner(object $partner, ?int $count = null): Course
    {
        Required($partner, 'partner');
        $course = $partner->badge;
        NotFound($course, 'course');

        return $course->load($this->loadableRelation($count));
    }

    public function findByPartner(object $partner, int $id): Course
    {
        Required($partner, 'parnter');
        $course = $partner->courses()->where('id', $id)->first();
        NotFound($course, 'course');

        return $course;
    }

    public function create(object $partner, array $data): Course
    {
        Required($data, 'course data');
        Truthy($data['in_public'] !== true , 'invalid court course operation');
        $course = $partner->courses()->create([
            'name' => $data['name'] ,
            'type' => $data['type'],
            'description' => $data['description'],
            'is_multiPerson' => $data['is_multiPerson'],
            'capacity' => $data['capacity'] ?? 1,
            'cost' => $data['cost'],
            'cancellation_cost' => $data['cancellation_cost'],
            'is_outdoor' => $data['is_outdoor'],
            'in_public'=>false,
        ]);
        if ($partner->courses()->count() === 1) {
            $this->setMain($partner, $course);
        }

        return $course;
    }

    public function update(Course $course, array $data): Course
    {
        Required($data, 'course data');
        $course->update($data);

        return $course;
    }

    public function delete(Course $course): bool
    {
        if ($course->is_main) {
            throw new Exception('course is main');
        }
        if (method_exists($course, 'deleteRelations')) {
            $course->deleteRelations();
        }

        return $course->delete();
    }

    public function setMain(object $partner, Course $course): Course
    {
        if ($course->is_main) {
            throw new Exception('course is main');
        }
        $main = $partner->courses()->where('is_main', true);
        if ($main->exists()) {
            $main->update(['is_main' => false]);
        }
        $course->update(['is_main' => true]);

        return $course;
    }

    public function toggleActivation(Course $course): bool
    {
        return $course->update(['is_active' => ! $course->is_active]);
    }

    private function loadableRelation(?int $count = null)
    {
        if (! $count === null) {
            $this->itemsCount = $count;
        }

        return [
            'workdays' => fn($query) => $query->limit($this->itemsCount),
            'activities' => fn($query) => $query->limit($this->itemsCount),
            'medias' => fn($query) => $query->limit($this->itemsCount),
            'location',
        ];
    }

}
