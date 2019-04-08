<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class Page extends ValidatorFactory
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
            ->field('title')
            ->required();

        // Body must not be empty if layout and layout ID are both empty
        $validator
            ->field('body')
            ->atLeastOne('layout_id', 'layout');

        $validator
            ->field('layout_id')
            ->uuid()
            ->atLeastOne('layout');

        $validator
            ->field('layout');

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
