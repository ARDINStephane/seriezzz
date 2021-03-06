<?php

namespace App\DataFixtures;

use App\Application\Doctrine\Entity\DoctrineUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new DoctrineUser();
        $user->setUsername('toto')
            ->setPassword($this->encoder->encodePassword($user, 'toto'))
            ->setRoles(['ROLE_USER']);
        $user1 = new DoctrineUser();
        $user1->setUsername('admin')
            ->setPassword($this->encoder->encodePassword($user, 'admin'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->persist($user1);

        $manager->flush();
    }
}
