<?php


namespace App\GraphQL\Resolver;


use Doctrine\ORM\EntityManager;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class AccountResolver implements ResolverInterface, AliasedInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em->getRepository('App:Account');
    }

    public function getAccount(Argument $args)
    {
        $account = $this->em->find($args['id']);
        return $account;
    }

    public function getAccounts(Argument $args)
    {
        $accounts = $this->em->findAll();
        return $accounts;
    }

    public static function getAliases()
    {
        return [
            'getAccount' => 'Account',
            'getAccounts' => 'Accounts'
        ];
    }
}
