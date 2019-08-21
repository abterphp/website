<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class PageCategory extends ValidatorFactory
{
    /**
     * @return IValidator
     */
    public function createValidator(): IValidator
    {
        $validator = parent::createValidator();

        $validator
            ->field('id')
            ->uuid();

        $validator
            ->field('identifier');

        $validator
            ->field('name')
            ->required();

        $validator
            ->field('user_group_ids');

        return $validator;
    }
}
