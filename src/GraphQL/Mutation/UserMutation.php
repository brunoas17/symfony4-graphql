<?php

namespace App\GraphQL\Mutation;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class UserMutation implements MutationInterface, AliasedInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createUser(Argument $args)
    {
        $input = $args['input'];

        $user = new User();

        $user->setName($input['name']);
        $user->setEmail($input['email']);

        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (ORMException $e) {
            return $e;
        }

        return $user;
    }

    public function updateUser(Argument $args)
    {
        $user = $this->em->getRepository('App:User')->find($args['id']);

        if(!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$args['id']
            );
        }

        $input = $args['input'];

        $user->setName($input['name']);
        $user->setEmail($input['email']);

        try {
            $this->em->flush();
        } catch (ORMException $e) {
            return $e;
        }

        return $user;
    }

    public function deleteUser(Argument $args)
    {
        $user = $this->em->getRepository('App:User')->find($args['id']);

        try {
            $this->em->remove($user);
            $this->em->flush();
        } catch (ORMException $e) {
            return $e;
        }
    }

    public static function getAliases()
    {
        return [
            "createUser" => "create_user",
            "updateUser" => "update_user",
            "deleteUser" => "delete_user"
        ];
    }
}
