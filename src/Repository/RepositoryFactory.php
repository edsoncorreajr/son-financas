<?php
declare (strict_types = 1);

namespace SONFin\Repository;

use SONFin\Repository\DefaultRepository;

class RepositoryFactory
{
    public static function factory(string $modelClass){
        return new DefaultRepository($modelClass);
    }
}