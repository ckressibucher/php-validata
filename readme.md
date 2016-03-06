Data validation
===============

The use case
------------

The idea is to validate objects of a given class, to
have certainity that it contains valid data.
The main use case is validating input data from
the HTTP Request

The idea
---------

What happens in almost all Web apps?

1. A Request comes in
2. The required input data is extracted and validated
3. If data is valid, some business logic is applied
4. A Response is created, either from the business logic's result or the errors that have occurred
   (they may be input validation or business logic errors)

There is some validation functionality provided by [PHP itself](http://php.net/manual/en/filter.examples.validation.php), as well as by additional libraries which I call "low level".
Other validation libraries are provided by, or for, specific "full stack frameworks".

As a contrast, this library focuses on validating complete data classes (which may represent
the data of your HTML form). It does not need any coupling to frameworks.
You provide the validation schema by defining classes for the expected data.

Usage
-------

```php
// the factory is probably created once and injected
// into your controller
$factory = new MyFactory();

// The factory is responsible to create validators for
// a given data class
$validator = $factory->createFor(MyDataClass::class);

// The validator validates the (unsafe) input data.
// On success, it returns your data class, on
// failure, it returns error definitions
list($data, $errs) = $validator->validate($_POST);
```


