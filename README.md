# Typed Classes and Objects

Updates for PHP8 (>=8.1).

This enables PHP objects to strictly define member structure, to control their data types, and most importantly, to add convenience methods for data sanitation. The master branch is considered to be in constant development and currently only supports PHP version 8.1 and above, and now handles strict typing and allows for properties with class typing to be set to null.

Object properties with public visibility will only use the built-in PHP type checking. Making the visibility protected or private in your data structure that inherits from **TypedClass** or **TypedArray** will use the **Diskerror\Typed** type checking. This mechanism silently ignores bad or unmapped property names and silently coerces input data into the best form represented by the property data type. Assigning the wrong data type to *public* properties will depend on the project's or file's `declare(strict_types=?);` setting.

Assisted by Google Gemini starting January 6, 2026. It's primary changes include caching class metadata, expanded the number of PHPUnit tests, and greatly improved the documentation.

## Philosophy & Intent

This library was primarily designed to handle the **sanitization** of data coming from HTTP `GET` or `POST` requests before storing it in an SQL or document database.

*   **Bridge between HTTP and DB:** It acts as a layer that takes loose, often string-based HTTP input and hydrates it into strictly typed PHP objects matching your database schema.
*   **Silent Coercion:** It assumes that **business logic validation** (e.g., ensuring a user is over 18) has already been performed on the client side (Javascript) or in a dedicated validation layer. Therefore, it "silently" coerces data to the correct type. For example, if a form sends `age="25"` (string), this library converts it to `25` (int) without complaining.
*   **Sanitization, not Validation:** It ensures *Type Safety* (an integer is an integer), but does not validate the *value* itself.

## Usage Examples

### Form Data Scenario (Sanitizing POST data)

```php
use Diskerror\Typed\TypedClass;

class UserProfile extends TypedClass
{
    // Protected properties trigger the "silent coercion" logic
    protected int $userId;
    protected string $username;
    protected ?int $age = null; // Nullable
    protected bool $isActive = false; // Default value
}

// Simulated $_POST data from a form
$postData = [
    'userId' => '42',         // String from form
    'username' => ' Alice ',  // Trailing space
    'age' => '',              // Empty string (common in forms)
    'isActive' => '1',        // '1' or 'on' often sent for checkboxes
    'extraField' => 'junk'    // Field not in our class
];

$user = new UserProfile($postData);

// Result:
// $user->userId is 42 (int)
// $user->username is ' Alice ' (string) - *Trim handled by specific Scalar types if needed*
// $user->age is null (empty string -> null for nullable types)
// $user->isActive is true (boolean conversion)
// 'extraField' is ignored.
```

### Property Mapping (Aliases)

You can map external field names (like `user_id` from a database) to your class properties (like `$userId`) using PHP 8 Attributes.

```php
use Diskerror\Typed\TypedClass;
use Diskerror\Typed\AtMap;

class User extends TypedClass {
    #[AtMap('user_id')]
    protected int $userId;
}

$user = new User(['user_id' => 123]);
// $user->userId is 123
```

*Note: If a conflict occurs, PHP 8 Attributes take precedence over the `protected $_map` array.*

### Persistence Workflow

Typical flow for saving data:

`HTTP Input` -> `TypedClass (Sanitize/Hydrate)` -> `SqlStatement` / `BSON` -> `Database`

### Overhead

There is significant overhead for instantiating a data structure using deritives of **Typed** classes. It is recommended that if you want to use this to sanitize similar objects in a loop then it is recommended to create a single object and clear it before assigning new data to it. This way the old data is removed before it is mistaken for new when an unset input property is encountered that would leave the old value unchanged.

# TypedAbstract

## Abstract Methods

### assign

Child class will copy all matching members by name while maintaining original types and doing a deep copy where appropriate. This method will silently ignore extra properties in the input object, and will skip names starting with an underscore (per Zend Framework coding style). EXCEPT: the property name "_id" is allowed for use with MongoDB.

Input can be an object or an array, null, or false. A null or false will set the **TypedAbstract** object to zero or empty values.

### merge

This method is similar to *assign* above except that it clones the current object and then replaces matching values with the input values and returns the new **TypedAbstract** object.

## Implemented Methods

### clear

Sets all members to null, zero, or empty. Objects will also have their members set to null, zero, or empty.

### toArray

Returns an associative array of this object with only the appropriate members, according to the *ConversionOptions* setting. A deep copy/conversion to an associative array from objects is also performed.

### jsonSerialize

This method will return an array when *json_encode()* is called passing in the object. This will have the options to omit empty values and maintain expressions. Dates will be converted to strings that include the time zone and fractional seconds to the microsecond.

### bsonSerialize

This method will return an array when **MongoDB\BSON\toPHP** or related methods are is called passing in the object. This will have the options to omit empty values. Be aware that BSON dates are only accurate to the millisecond and are always converted to UTC.

The interfaces **MongoDB\BSON\Serializable** and **MongoDB\BSON\Unserializable** are implemented instead of **MongoDB\BSON\Persistable** because creating new **Diskerror\Typed** objects requires muchmore overhead than assigning a document returned by MongoDB to an existing and cleared **Diskerror\Typed** object. See: https://www.php.net/manual/en/mongodb.persistence.php

### _setBasicTypeAndConfirm

This static method references the input value and casts it to the basic requested type.

# TypedClass

The derivatives of **TypedClass** are contracted to do these things:

* Member/property access will behave like any standard PHP object, even when visibility set to "protected" or "private".
* Maintain the initial type of each member/property.
* Silently cast data assigned to non-public properties in the most obvious way when input member to *assign*, or *merge* is of a different type than the corresponding local property.
* Recognize classes inherited from **AtomicInterface** to manage their values internally.
* Handle special cases of members/properties that are objects with an option for handling NULL assignments.
* Implement “toArray” to return a deeply transformed data structure to a standard associative array.
* Return proper return values and types for *json_encode* and *bsonSerialize*.
* Accept another object, associative or indexed array, and assign the input values to the appropriate members.
    * Copy each field or property item by item.
    * Copy indexed array by position.
    * Map alternate names to proper names.
    * Clear all values without rebuilding the object.

The users' class properties must be declared as "protected" or "private" for this contract to work. The names for the properties must follow the naming convention that the intended "public" members must not start with an underscore. This borrows from the Zend Framework property naming convention of protected and private property names starting with an underscore.

If a non-public property has no type then it is assumed to accept any type and null values. If a property has no type but has been assigned an initial value then the type will be assumed to be of the initial value.

Even more complex examples can be found in the "tests" directory.

# TypedArray

The instances or derivatives of **TypedArray** are contracted to do these things:

* Behave like a standard PHP associative array.
* Insure every member be the same type.
* Silently cast assigned data in the most obvious way.
* Implement “toArray” to return a deeply transformed PHP associative array.
* Handle the functions *json_encode* and *bsonSerialize* appropriately.

# DateTime and Date

These classes add convenience methods to the built-in PHP **DateTime** class. This includes the *__toString* method that returns a date-time string formatted for the initValue MySQL date-time format. This includes fractional seconds to the microsecond.

# SqlStatement

Utility class that outputs properly formatted partial SQL strings based on the input data. Both accept an input array, and an array where the values are the names of members to include from the first array. An empty array means to use all members.

## toInsert

Returns a string formatted for an SQL INSERT or UPDATE statement. Accepts an array where the values are the names of members to include. An empty second array parameter means to use all members in the first array parameter.

## toValues

Returns a string formatted for an SQL "ON DUPLICATE KEY UPDATE" statement.

# Composer

```
> composer require diskerror/typed
```
