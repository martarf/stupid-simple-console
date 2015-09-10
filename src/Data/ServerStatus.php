<?php

namespace PNWPHP\SSC\Data;

class ServerStatus
{
    private $name;
    private $isOnline;
    private $isGroup;
    private $numActive;

    public function __construct(
        $name,
        $isOnline,
        $isGroup,
        $numActive
    )
    {
        $this->name = (string)$name;
        $this->isOnline = (bool)$isOnline;
        $this->isGroup = (bool)$isGroup;
        $this->numActive = (int)$numActive;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isOnline()
    {
        return $this->isOnline;
    }

    /**
     * @return bool
     */
    public function isGroup()
    {
        return $this->isGroup;
    }

    /**
     * @return int
     */
    public function numActive()
    {
        return $this->numActive;
    }
}
