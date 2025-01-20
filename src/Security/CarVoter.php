<?php

namespace App\Security;

use App\Entity\Car;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CarVoter extends Voter
{
    private const EDIT   = 'edit';
    private const DELETE = 'delete';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE]) && $subject instanceof Car;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->security->getUser();

        if (!$user){
            return false;
        }

        $car = $subject;
        return $car->getAuthor() === $user;
    }
}
