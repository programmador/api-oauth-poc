<?php

namespace App\DataFixtures;

use App\Entity\User as Entity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class User extends Fixture implements ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $config = $this->container->getParameter('datafixtures');
        $users = $config['users'];
        foreach ($users as $user) {
            extract($user);
            $entity = new Entity();
            $entity->setName($name);
            $entity->setPassword($password);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}