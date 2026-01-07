# Diskerror/Typed - Strict Typing for PHP Objects

This project is a PHP library designed to enforce strict typing on object properties and array elements. It provides a robust mechanism for data sanitation, type coercion, and structured serialization.

## Project Overview

**Purpose:** To enable PHP objects to strictly define their member structure and control data types. It allows for "silent" coercion of input data into the expected types, making it useful for processing external data (like API requests) into structured objects.

**Key Components:**
*   **`Diskerror\Typed\TypedClass`**: An abstract base class for creating objects with strictly typed properties. It uses PHP's type system (via Reflection) to enforce types.
*   **`Diskerror\Typed\TypedArray`**: A class that enforces a single type for all elements within an array structure.
*   **`Diskerror\Typed\TypedAbstract`**: The common ancestor providing shared functionality like `assign`, `merge`, `clear`, and serialization methods.

**Supported Features:**
*   **Strict Typing:** Enforces types for properties (int, float, string, bool, objects, etc.).
*   **Automatic Casting:** Safely casts input data to the target property type when assigning values.
*   **Serialization:** Built-in support for `toArray`, `jsonSerialize` (for `json_encode`), and `bsonSerialize` (for MongoDB).
*   **Deep Copying:** Handles deep copying of nested objects during assignment and merging.

### Philosophy & Intent

*   **Explicit Use Case:** The library is designed as a bridge between loose HTTP Request data (`$_GET`, `$_POST`, JSON bodies) and strict Database schemas.
*   **Silent Coercion:** It intentionally casts types silently (e.g., converting a string "123" from a form submission into an integer `123`) to prevent trivial type mismatches from causing fatal errors.
*   **Assumption of Validity:** The library assumes that **business logic validation** (e.g., "age must be > 18") happens on the client side or in a separate validation layer. This library handles **Type Sanitization**, ensuring that if a property says it's an integer, it *is* an integer (or 0/null), but it does not validate the *content* of that integer.

## Requirements

*   **PHP:** >= 8.1
*   **Extensions:** `ext-json`, `ext-intl`
*   **Optional:** `ext-mongodb` (for BSON serialization), `laminas/laminas-json`

## Building and Running

### 1. Installation

This project uses [Composer](https://getcomposer.org/) for dependency management.

```bash
composer install
```

### 2. Running Tests

The project uses [PHPUnit](https://phpunit.de/) for testing. The configuration is defined in `phpunit.xml`.

```bash
# Run all tests
vendor/bin/phpunit

# Run a specific test suite (e.g., 'assign')
vendor/bin/phpunit --testsuite assign
```

## Development Conventions

*   **Namespace:** `Diskerror\Typed\` maps to the `src/` directory (PSR-4).
*   **Property Naming:**
    *   **Public:** Standard camelCase or snake_case.
    *   **Protected/Private:** Should start with an underscore `_` (e.g., `$_meta`), adhering to legacy Zend Framework styles, *except* for `_id` which is allowed for MongoDB compatibility.
*   **Type Declaration:**
    *   Use standard PHP type hints for properties.
    *   Nullable types (e.g., `?string`) are supported.
    *   Initialized values in property declarations determine the default type if no explicit type is given (though explicit typing is preferred).
*   **Visibility:**
    *   Public properties use standard PHP type checking.
    *   Protected/Private properties benefit from the library's "magic" `__get`/`__set` methods which handle the automatic casting/sanitization logic. **This is a key usage pattern of the library.**

## Best Practices

*   **Visibility Strategy:** To utilize the library's core feature (silent coercion/sanitization), declare properties as `protected` or `private`. If you declare them as `public`, PHP's standard strict typing applies, bypassing the library's sanitation logic.
*   **Performance:** There is overhead involved in the reflection and "magic" methods used to enforce types and coercion.
    *   **Recommendation:** When processing large loops of data, instantiate a single `TypedClass` object and reuse it by calling `$obj->clear()` and then `$obj->assign($newData)` for each iteration, rather than creating a new object instance every time.

## Key Classes and Usage

### `TypedClass`
Inherit from this class to create strictly typed data objects.

```php
use Diskerror\Typed\TypedClass;

class User extends TypedClass {
    protected string $name;
    protected int $age;
    protected ?string $email = null;
}

$user = new User(['name' => 'Alice', 'age' => '30']);
// $user->name is 'Alice', $user->age is 30 (integer)
```

#### Property Mapping with Attributes (PHP 8)
You can use the `#[AtMap]` attribute to map incoming keys (e.g., from a database or API) to your class properties.

```php
use Diskerror\Typed\TypedClass;
use Diskerror\Typed\AtMap;

class User extends TypedClass {
    #[AtMap('user_id')]
    protected int $userId;

    #[AtMap('full_name')]
    protected string $name;
}

$user = new User(['user_id' => 101, 'full_name' => 'Alice']);
// $user->userId is 101
// $user->name is 'Alice'
```

**Precedence:** If the same input key is defined in both an `#[AtMap]` attribute and the `$_map` array, the attribute mapping takes precedence.

Legacy support for the `$_map` array property is also maintained.

### `TypedArray`
Use this to create an array where every item must be of a specific type.

```php
use Diskerror\Typed\TypedArray;

// Array of integers
$integers = new TypedArray('int', [1, '2', 3.5]);
// Result: [1, 2, 3] (auto-casted)
```

#### Inheriting from `TypedArray`
You can also create a specialized array class by inheriting from `TypedArray`. Define the `$_type` property in your child class. When checking types, the class will look for this property.

```php
use Diskerror\Typed\TypedArray;

class UserList extends TypedArray {
    // Define the type for items in this array
    protected string $_type = User::class; 
}

// Usage: The constructor now only takes the data array
$users = new UserList([
    ['name' => 'Bob', 'age' => 40],
    $existingUserObject
]);
```
