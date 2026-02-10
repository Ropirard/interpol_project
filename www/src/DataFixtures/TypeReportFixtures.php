<?php

namespace App\DataFixtures;

use App\Entity\TypeReport;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TypeReportFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            [
                'label' => 'J’ai aperçu cette personne récemment et je pense que cette information peut être utile',

            ],
            [
                'label' => 'Je connais personnellement cette personne et je peux fournir des informations supplémentaires',

            ],
            [
                'label' => 'J’ai été témoin d’un comportement ou d’une activité suspecte impliquant cette personne',

            ],
            [
                'label' => 'Cette information m’a été rapportée par un tiers et mérite vérification',

            ],
        ];

        foreach ($types as $value) {
            $type = new TypeReport();
            $type->setLabel($value['label']);
            $manager->persist($type);
        }

        $manager->flush();
    }
}
