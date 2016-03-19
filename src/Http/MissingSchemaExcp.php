<?php


namespace Ckr\Validata\Http;

class MissingSchemaExcp extends \RuntimeException
{
    public $clazz;

    public static function make($clazz, $message = null)
    {
        $message = $message ?: sprintf('No schema defined in class=%s', $clazz);
        $e = new self($message);
        $e->clazz = $clazz;
        return $e;
    }
}
