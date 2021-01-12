<?php


namespace App\GraphQL\Mutation;


use App\Entity\Account;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class AccountMutation implements MutationInterface, AliasedInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createAccount(Argument $args)
    {
        $input = $args['input'];

        $account = new Account();

        $account->setName($input['name']);

        if($user = $this->em->getRepository('App:User')->find($input['user_id'])) {
            $account->setUser($user);
        }

        if($accountType = $this->em->getRepository('App:AccountType')->find($input['account_type_id'])) {
            $account->setAccountType($accountType);
        }

        try {
            $this->em->persist($account);
            $this->em->flush();
        } catch (ORMException $e) {
            return $e;
        }

        return $account;
    }

    public function updateAccount(Argument $args)
    {
        $account = $this->em->getRepository('App:Account')->find($args['id']);

        if(!$account) {
            throw $this->createNotFoundException(
                'No account found for id '.$args['id']
            );
        }

        $input = $args['input'];

        $account->setName($input['name']);

        if($user = $this->em->getRepository('App:User')->find($input['user_id'])) {
            $account->setUser($user);
        }

        if($accountType = $this->em->getRepository('App:AccountType')->find($input['account_type_id'])) {
            $account->setAccountType($accountType);
        }

        try {
            $this->em->flush();
        } catch (ORMException $e) {
            return $e;
        }

        return $account;
    }

    public function deleteAccount(Argument $args)
    {
        $account = $this->em->getRepository('App:Account')->find($args['id']);

        $this->em->remove($account);
        $this->em->flush();

    }

    public static function getAliases()
    {
        return [
            "createAccount" => "create_account",
            "updateAccount" => "update_account",
            "deleteAccount" => "delete_account"
        ];
    }
}
