<?php

namespace Dpn;

/**
 * Class NormalizerObjectTest
 * @package Dpn
 */
class NormalizerObjectTest
{
    private $notDefault;
    private $int;
    private $string;

    /**
     * NormalizerObjectTest constructor.
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
        $this->string = $string;
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
     * @return null|string
     */
    public function getString(): ?string
    {
        return $this->string;
    }
}
