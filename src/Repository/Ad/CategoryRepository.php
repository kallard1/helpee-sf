<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\Repository\Ad;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * Class CategoryRepository.
 */
class CategoryRepository extends NestedTreeRepository
{
    /**
     * @return array
     */
    public function findAll()
    {
        return $this->findBy([], ['root' => 'ASC', 'lft' => 'ASC']);
    }
}
