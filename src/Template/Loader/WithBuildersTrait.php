<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Loader;

use AbterPhp\Framework\Exception\Config;
use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ParsedTemplate;

trait WithBuildersTrait
{
    /**
     * @var IBuilder[]
     */
    protected $builders;

    /**
     * @param string   $name
     * @param IBuilder $builder
     *
     * @return $this
     */
    public function addBuilder(string $name, IBuilder $builder): self
    {
        $this->builders[$name] = $builder;

        return $this;
    }

    /**
     * @param ParsedTemplate $parsedTemplate
     * @param mixed          $data
     * @param string|null    $defaultBuilder
     *
     * @return IData
     */
    protected function buildTemplateData(ParsedTemplate $parsedTemplate, $data, ?string $defaultBuilder = null): IData
    {
        $builderName = $parsedTemplate->getAttribute('builder', $defaultBuilder);
        if (!array_key_exists($builderName, $this->builders)) {
            throw new Config(__CLASS__);
        }

        return $this->builders[$builderName]->build($data, $parsedTemplate);
    }
}
