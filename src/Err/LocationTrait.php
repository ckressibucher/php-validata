<?php


namespace Ckr\Validata\Err;


trait LocationTrait
{

    public abstract function getType();

    public abstract function getLocation();

    public function __toString()
    {
        return $this->getType() . ':' . strval($this->getLocation());
    }

}