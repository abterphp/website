<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class Block extends ValidatorFactory
{
    /**
     * @return IValidator
     */
    public function createValidator(): IValidator
    {
        $validator = parent::createValidator();

        $validator
            ->field('id')
            ->forbidden();

        $validator
            ->field('identifier');

        $validator
            ->field('title')
            ->required();

        $validator
            ->field('body');

        $validator
            ->field('layout_id')
            ->uuid();

        $validator
            ->field('layout')
            ->validateEmpty()
            ->exactlyOne('layout_id');

        return $validator;
    }
}
