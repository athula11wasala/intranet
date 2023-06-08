<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Teams;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach($this->getCountryName() as $row){
        
         $country = new  Country();
         $country->setName($row);
         $manager->persist($country);

        $manager->flush();
        }
    }

    public function getCountryName(){

        return [
            'AUSTRALIA','BELGIUM','CANADA','INDIA','ITALY','NETHERLANDS','POLAND','SOUTH AFRICA','ENGLAND','SPAIN',
            'SWEDEN'
        ];
    }
}
