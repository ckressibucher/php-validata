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

// ======== alternative idea =============
// let controller define one data class, which is passed to the action method.
// the data class defines properties with annotations that define the validation
// it may also define sequences and maps with additional data classes.
// This annotated data classes allow the framework to build the validation schema.
// Before the action is called, the data class is instantiated and populated with
// the input data.
// Maybe allow other formats to define the meta data for the data classes (to
// allow non-annotation opcache).
// See symfony/validation for inspiration
