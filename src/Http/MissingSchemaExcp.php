<?php


namespace Ckr\Validata\Http;

class MissingSchemaExcp extends \RuntimeException
{
    public $clazz;

    public static function make($clazz)
    {
        $e = new self();
        $e->clazz = $clazz;
        return $e;
    }
}
