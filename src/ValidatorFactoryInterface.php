<?php

namespace Ckr\Validata;

interface ValidatorFactoryInterface
{

    /**
     * @param string $clazz The data class for which the validator should run
     * @return ValidatorInterface
     */
    public function createFor($clazz);
}