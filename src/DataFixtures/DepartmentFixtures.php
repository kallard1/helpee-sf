<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Department;
use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DepartmentFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            RegionFixtures::class,
        ];
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->departmentData() as [$region, $code, $name]) {
            $department = new Department();
            $region = $manager->getRepository(Region::class)->findOneBy(['code' => $region]);

            $department->setRegion($region);
            $department->setCode($code);
            $department->setName($name);

            $manager->persist($department);
        }

        $manager->flush();
    }

    private function departmentData(): array
    {
        return [
            ['84', '01', 'Ain'],
            ['32', '02', 'Aisne'],
            ['84', '03', 'Allier'],
            ['93', '04', 'Alpes-de-Haute-Provence'],
            ['93', '05', 'Hautes-Alpes'],
            ['93', '06', 'Alpes-Maritimes'],
            ['84', '07', 'Ardèche'],
            ['44', '08', 'Ardennes'],
            ['76', '09', 'Ariège'],
            ['44', '10', 'Aube'],
            ['76', '11', 'Aude'],
            ['76', '12', 'Aveyron'],
            ['93', '13', 'Bouches-du-Rhône'],
            ['28', '14', 'Calvados'],
            ['84', '15', 'Cantal'],
            ['75', '16', 'Charente'],
            ['75', '17', 'Charente-Maritime'],
            ['24', '18', 'Cher'],
            ['75', '19', 'Corrèze'],
            ['27', '21', 'Côte-d\'Or'],
            ['53', '22', 'Côtes-d\'Armor'],
            ['75', '23', 'Creuse'],
            ['75', '24', 'Dordogne'],
            ['27', '25', 'Doubs'],
            ['84', '26', 'Drôme'],
            ['28', '27', 'Eure'],
            ['24', '28', 'Eure-et-Loir'],
            ['53', '29', 'Finistère'],
            ['94', '2A', 'Corse-du-Sud'],
            ['94', '2B', 'Haute-Corse'],
            ['76', '30', 'Gard'],
            ['76', '31', 'Haute-Garonne'],
            ['76', '32', 'Gers'],
            ['75', '33', 'Gironde'],
            ['76', '34', 'Hérault'],
            ['53', '35', 'Ille-et-Vilaine'],
            ['24', '36', 'Indre'],
            ['24', '37', 'Indre-et-Loire'],
            ['84', '38', 'Isère'],
            ['27', '39', 'Jura'],
            ['75', '40', 'Landes'],
            ['24', '41', 'Loir-et-Cher'],
            ['84', '42', 'Loire'],
            ['84', '43', 'Haute-Loire'],
            ['52', '44', 'Loire-Atlantique'],
            ['24', '45', 'Loiret'],
            ['76', '46', 'Lot'],
            ['75', '47', 'Lot-et-Garonne'],
            ['76', '48', 'Lozère'],
            ['52', '49', 'Maine-et-Loire'],
            ['28', '50', 'Manche'],
            ['44', '51', 'Marne'],
            ['44', '52', 'Haute-Marne'],
            ['52', '53', 'Mayenne'],
            ['44', '54', 'Meurthe-et-Moselle'],
            ['44', '55', 'Meuse'],
            ['53', '56', 'Morbihan'],
            ['44', '57', 'Moselle'],
            ['27', '58', 'Nièvre'],
            ['32', '59', 'Nord'],
            ['32', '60', 'Oise'],
            ['28', '61', 'Orne'],
            ['32', '62', 'Pas-de-Calais'],
            ['84', '63', 'Puy-de-Dôme'],
            ['75', '64', 'Pyrénées-Atlantiques'],
            ['76', '65', 'Hautes-Pyrénées'],
            ['76', '66', 'Pyrénées-Orientales'],
            ['44', '67', 'Bas-Rhin'],
            ['44', '68', 'Haut-Rhin'],
            ['84', '69', 'Rhône'],
            ['27', '70', 'Haute-Saône'],
            ['27', '71', 'Saône-et-Loire'],
            ['52', '72', 'Sarthe'],
            ['84', '73', 'Savoie'],
            ['84', '74', 'Haute-Savoie'],
            ['11', '75', 'Paris'],
            ['28', '76', 'Seine-Maritime'],
            ['11', '77', 'Seine-et-Marne'],
            ['11', '78', 'Yvelines'],
            ['75', '79', 'Deux-Sèvres'],
            ['32', '80', 'Somme'],
            ['76', '81', 'Tarn'],
            ['76', '82', 'Tarn-et-Garonne'],
            ['93', '83', 'Var'],
            ['93', '84', 'Vaucluse'],
            ['52', '85', 'Vendée'],
            ['75', '86', 'Vienne'],
            ['75', '87', 'Haute-Vienne'],
            ['44', '88', 'Vosges'],
            ['27', '89', 'Yonne'],
            ['27', '90', 'Territoire de Belfort'],
            ['11', '91', 'Essonne'],
            ['11', '92', 'Hauts-de-Seine'],
            ['11', '93', 'Seine-Saint-Denis'],
            ['11', '94', 'Val-de-Marne'],
            ['11', '95', 'Val-d\'Oise'],
            ['01', '971', 'Guadeloupe'],
            ['02', '972', 'Martinique'],
            ['03', '973', 'Guyane'],
            ['04', '974', 'La Réunion'],
            ['06', '976', 'Mayotte'],
            ['COM', '975', 'Saint-Pierre-et-Miquelon'],
            ['COM', '977', 'Saint-Barthélemy'],
            ['COM', '978', 'Saint-Martin'],
            ['COM', '984', 'Terres australes et antarctiques françaises'],
            ['COM', '986', 'Wallis et Futuna'],
            ['COM', '987', 'Polynésie française'],
            ['COM', '988', 'Nouvelle-Calédonie'],
            ['COM', '989', 'Île de Clipperton'],
        ];
    }
}
