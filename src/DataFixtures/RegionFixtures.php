<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RegionFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->regionData() as [$code, $name]) {
            $region = new Region();

            $region->setCode($code);
            $region->setName($name);

            $manager->persist($region);
        }

        $manager->flush();
    }

    private function regionData(): array
    {
        return [
            ['01', 'Guadeloupe'],
            ['02', 'Martinique'],
            ['03', 'Guyane'],
            ['04', 'La Réunion'],
            ['06', 'Mayotte'],
            ['11', 'Île-de-France'],
            ['24', 'Centre-Val de Loire'],
            ['27', 'Bourgogne-Franche-Comté'],
            ['28', 'Normandie'],
            ['32', 'Hauts-de-France'],
            ['44', 'Grand Est'],
            ['52', 'Pays de la Loire'],
            ['53', 'Bretagne'],
            ['75', 'Nouvelle-Aquitaine'],
            ['76', 'Occitanie'],
            ['84', 'Auvergne-Rhône-Alpes'],
            ['93', 'Provence-Alpes-Côte d\'Azur'],
            ['94', 'Corse'],
            ['COM', 'Collectivités d\'Outre-Mer'],
        ];
    }
}
