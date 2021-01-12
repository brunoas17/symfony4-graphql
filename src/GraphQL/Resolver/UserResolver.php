<?php

namespace App\GraphQL\Resolver;

use Doctrine\ORM\EntityManager;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Utils\Value;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class UserResolver implements ResolverInterface, AliasedInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em->getRepository('App:User');
    }
    
    public function getUser(Argument $args)
    {
        $user = $this->em->find($args['id']);
        return $user;
    }

    public function getUsers()
    {
        return $this->em->findAll();
    }

    public static function getAliases()
    {
        return [
            'getUser' => 'User',
            'getUsers' => 'Users'
        ];
    }
}
