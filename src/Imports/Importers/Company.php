<?php

namespace LaravelEnso\Companies\Imports\Importers;

use LaravelEnso\Companies\Models\Company as Model;
use LaravelEnso\Core\Models\User;
use LaravelEnso\DataImport\Contracts\Importable;
use LaravelEnso\Helpers\Services\Obj;

class Company implements Importable
{
    public function run(Obj $row, User $user, Obj $params)
    {
        $this->import($row);
    }

    private function import($row)
    {
        (Model::class)::factory()->create($row->toArray());
    }
}
