<?php

namespace App\Exports;

use App\Models\Member;

class RegionMembersExport extends MembersExport
{
    public function __construct(
        private int $regionId,
        array $filters = []
    ) {
        parent::__construct($filters);
    }

    public function query()
    {
        return parent::query()->where('registered_by_region_id', $this->regionId);
    }
}