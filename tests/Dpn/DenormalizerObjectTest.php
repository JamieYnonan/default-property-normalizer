<?php

namespace Dpn;

/**
 * Class DenormalizerObjectTest
 * @package Dpn
 */
class DenormalizerObjectTest
{
    private $notDefault;
    private $int;
    private $string;
    private $float;

    /**
     * DenormalizerObjectTest constructor.
     * @param bool $notDefault
     */
    public function __construct(bool $notDefault)
    {
        $this->notDefault = $notDefault;
    }


    /**
     * @param int|null $int
     */
    public function setInt(?int $int): void
    {
        $this->int = $int;
    }

    /**
     * @param null|string $string
     */
    public function setString(string $string): void
    {
        $this->string = 'test '. $string;
    }

    /**
     * @return bool
     */
    public function isNotDefault(): bool
    {
        return $this->notDefault;
    }

    /**
     * @return int|null
     */
    public function getInt(): ?int
    {
        return $this->int;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @return float
     */
    public function getFloat(): float
    {
        return $this->float;
    }
}
