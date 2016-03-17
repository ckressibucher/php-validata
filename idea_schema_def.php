<?php

use Ckr\Validata\Schema;
use Ckr\Validata\Schema\Scalar;
use Respect\Validation\Rules;

// idea: use DI container
// for this, schemas have to be immutable, so
// they can be shared.
function diContainerInit(Container $c) {
    $c['nameSchema'] = new Scalar(new Rules\Alnum);
    $c['email'] = new Scalar(new Rules\Email);
    $c['tel'] = new Scalar(new Rules\Phone);
    $c['street'] = new Scalar(new Rules\Alnum);

    $c['address'] = function($c) {
        return (new Schema\Map())
            ->property('first-name', $c['name'])
            ->property('last-name', $c['name'])
            ->property('street', $c['street'])
            ->property('email', $c['email']);
    };

    $c[ControllerAction::class] = function($c) {
        $schema = $c['address'];
        return new ControllerAction($schema);
    };
}
