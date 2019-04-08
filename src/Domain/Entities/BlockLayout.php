<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;

class BlockLayout implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $identifier;

    /** @var string */
    protected $body;

    /**
     * Page constructor.
     *
     * @param string $id
     * @param string $identifier
     * @param string $body
     */
    public function __construct(string $id, string $identifier, string $body)
    {
        $this->id         = $id;
        $this->identifier = $identifier;
        $this->body       = $body;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function setIdentifier(string $identifier): BlockLayout
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody(string $body): BlockLayout
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getIdentifier();
    }
}
