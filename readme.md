Data validation
===============

A library to validate structured data against a schema.

Status
-----

[![Build Status](https://travis-ci.org/ckressibucher/php-validata.svg)](https://travis-ci.org/ckressibucher/php-validata)
[![Coverage Status](https://coveralls.io/repos/github/ckressibucher/php-validata/badge.svg?branch=master)](https://coveralls.io/github/ckressibucher/php-validata?branch=master)

This library is currently actively developed, and changed to its API should be expected.
If you want to use it in production, please check the status again in a few weeks...

Why and how
---------

Most common web applications handle *structural* input data.
Examples are:

* Data sent by HTML forms
* JSON or XML Data for AJAX Requests
* JSON or XML Data sent to server APIs

In all cases, the data has to be validated. Often enough, this
validation is mixed with other tasks, maybe even with business logic.
The goal of this library is to separate the validation step from
the business logic.

How does it work? This library provides:

* Primitives to define a validation schema, and to validate input data against this schema.
* Classes representing results and describing errors including their exact location.

The intended usage is:

1. For each controller action that handles non trivial input data, a schema is defined.
   This schema defines the structure of the whole data, as well as additional characteristics
   of each single value.
2. The input data is validated against this schema. The result contains two parts:
   - the part of the input data that is valid
   - a list of errors, each containing detailed information about the location and the
     exact type of error. This information can be used to report errors to the client.
3. The controller action decides how it handles the result. Mostly, if there are any
   errors, the action will send a 400 HTTP response including information about the errors.
   If there are no errors, it may invoke some business modules to further process the data.

Dependencies
---------

For "low level validations", i.e. to validate single scalar values,
the [Respect Validation Library](https://github.com/Respect/Validation) is used.
There are no dependencies to any full stack frameworks.
The library is desiged to be easily integrated into any application structure.

Usage
----

```php

use Ckr\Validata\Schema;
use Ckr\Validata\Validator;
use Respect\Validation\Rules;

function action()
{
    $schema = getValidationSchema();
    $result = Validator::run($schema, $_POST);
    if ($result->hasErrors()) {
        handleErrors($result->getErrors());
    } else {
        handleRequest($result->getValidData());
    }
}

/**
 * Builds the validation schema for `action`
 */
function getValidationSchema()
{
    $mainSchema = new Schema\Map();
    $emailSchema = new Schema\Scalar(new Rules\Email());
    $usernameSchema = new Schema\Scalar(new Rules\Alnum());
    $mainSchema->property('email', $emailSchema);
    $mainSchema->property('username', $usernameSchema);

    return $mainSchema;
}

function handleErrors(array $errors)
{
    // TODO handle errors...
}

function handleRequest(array $validData)
{
    // TODO implement the actual business logic
}
```

While this looks like lots of code, much of it is always the same and can
be abstracted. How this is done, varies with the framework or application structure.

Especially the error handling can probably be reused for all actions of the same API or
application, as the errors have detailed information, that can easily be transformed in
a JSON Response (or any other structured data format).

The creation of the schema is a bit tedious. I will probably provide builders or similar
helpers in near future to make composing various subschemas easier.

