<?php

namespace App\Form\DataTransformer;

use App\Entity\CarCategoryEnum;
use Symfony\Component\Form\DataTransformerInterface;

class CarCategoryEnumToStringTransformer implements DataTransformerInterface
{
    public function transform($value): mixed
    {
        if ($value instanceof CarCategoryEnum) {
            return $value->value;
        }

        return $value;
    }

    public function reverseTransform($value): mixed
    {
        if (is_string($value) || is_int($value)) {
            return CarCategoryEnum::from($value);
        }

        if ($value instanceof CarCategoryEnum) {
            return $value;
        }

        throw new \UnexpectedValueException('Erreur de transformation');
    }
}
