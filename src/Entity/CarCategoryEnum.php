<?php

namespace App\Entity;

enum CarCategoryEnum: string
{
    case Compact = 'compact';
    case Suv = 'suv';
    case Crossover = 'crossover';
    case Utility = 'utility';
    case Pickup = 'pickup';
    case Supercar = 'supercar';
    case Other = 'other';
}
