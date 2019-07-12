<?php

declare(strict_types=1);

namespace App\DataFixtures\Ad;

use App\Entity\Ad\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->parentCategory($manager);

        foreach ($this->categoryData() as [$parent, $label]) {
            $category = new Category();

            $category->setParent($manager->getRepository(Category::class)->findOneBy(['label' => $parent]));
            $category->setLabel($label);

            $manager->persist($category);
        }

        $manager->flush();
    }

    private function parentCategory(ObjectManager $manager)
    {
        $data = [
            ['Véhicules'],
            ['Loisirs'],
            ['Multimédia'],
            ['Maison'],
            ['Services'],
            ['Vêtements'],
            ['Univers Bébé'],
            ['Autres'],
        ];

        foreach ($data as [$label]) {
            $category = new Category();

            $category->setLabel($label);

            $manager->persist($category);
        }

        $manager->flush();
    }

    private function categoryData()
    {
        return [
            ['Véhicules', 'Voiture'],
            ['Véhicules', 'Moto'],
            ['Véhicules', 'Utilitaires'],
            ['Loisirs', 'Vélo'],
            ['Loisirs', 'DVD / Films'],
            ['Loisirs', 'CD / Musique'],
            ['Loisirs', 'Jeux & Jouets'],
            ['Loisirs', 'Livres'],
            ['Loisirs', 'Animaux'],
            ['Loisirs', 'Sport'],
            ['Multimédia', 'Informatique'],
            ['Multimédia', 'Console & Jeux vidéos'],
            ['Multimédia', 'Image & Son'],
            ['Multimédia', 'Téléphonie'],
            ['Maison', 'Électroménager'],
            ['Maison', 'Jardinage'],
            ['Maison', 'Bricolage'],
            ['Maison', 'Ameublement'],
            ['Services', 'Perstations de service'],
            ['Services', 'Cours particuliers'],
            ['Services', 'Co-voiturage'],
            ['Services', 'Garde d\'enfants'],
            ['Vêtements', 'Homme'],
            ['Vêtements', 'Femme'],
            ['Vêtements', 'Enfant'],
            ['Vêtements', 'Maternité'],
            ['Univers Bébé', 'Équipement bébé'],
            ['Univers Bébé', 'Vêtements bébé'],
        ];
    }
}
