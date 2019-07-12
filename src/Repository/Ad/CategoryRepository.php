<?php

declare(strict_types=1);

namespace App\Repository\Ad;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository {
    public function findAll()
    {
        return $this->findBy([], ['root' => 'ASC', 'lft' => 'ASC']);
    }
}
