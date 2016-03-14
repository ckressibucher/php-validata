<?php

// idea: use DI container
// for this, schemas have to be immutable, so
// they can be shared.
function diContainerInit(Container $c) {
    $c['nameSchema'] = new Scalar(new Alnum));
    $c['email'] = new Scalar(new Email));
    $c['tel'] = new Scalar(new Tel));
    $c['street'] = new Scalar(new Alnum));

    $c['address'] = function($c) {
        return new Schema\Map()
            ->property('first-name', $c['name'])
            ->property('last-name', $c['name'])
            ->property('street', $c['street')
            ->property('email', $c['email'])
    };

    $c[ControllerAction::class] = function($c) {
        $schema = $c['address'];
        return new ControllerAction($schema);
    }
}
