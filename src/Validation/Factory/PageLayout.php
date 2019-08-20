<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class PageLayout extends ValidatorFactory
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
            ->field('identifier')
            ->required();

        $validator
            ->field('body');

        $validator
            ->field('header');

        $validator
            ->field('footer');

        $validator
            ->field('css_files');

        $validator
            ->field('js_files');

        return $validator;
    }
}
