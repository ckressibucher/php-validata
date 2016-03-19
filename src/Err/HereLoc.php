<?php


namespace Ckr\Validata\Err;

/**
 * Error happened "right here"
 * (used when an "end" value -- e.g. a scalar -- is validated)
 */
class HereLoc implements LocationInterface
{

    use LocationTrait;

    const TYPE = 'here';

    private static $instance;

    /**
     * Returns a singleton of this class
     * (as there is no value contained, there's no
     * need for multiple instances of this class)
     *
     * @return HereLoc
     */
    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * Returns a value describing the location.
     * The type of this value depends on the `getType` result
     *
     * @return string|int
     */
    public function getLocation()
    {
        return '';
    }
}
