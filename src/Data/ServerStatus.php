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

    public function name()
    {
        return $this->name;
    }

    public function isOnline()
    {
        return $this->isOnline;
    }
}
