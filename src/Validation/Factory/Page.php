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
            ->forbidden();

        $validator
            ->field('identifier');

        $validator
            ->field('classes');

        $validator
            ->field('title')
            ->required();

        $validator
            ->field('lead');

        $validator
            ->field('body');

        $validator
            ->field('is_draft')
            ->numeric();

        $validator
            ->field('category_id')
            ->uuid();

        $validator
            ->field('layout_id')
            ->uuid();

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
