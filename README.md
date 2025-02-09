# Typed Classes and Objects

Updates for PHP8 (>=8.2).

This enables PHP objects to strictly define member structure, to control their data types, and to add convenience methods data sanitation. The master branch is considered to be in constant development and currently only supports PHP version 8.2 and above, and now handles strict typing and allows for properties with class typing to be set to null.

Object properties with public visibility will only use the built-in PHP type checking. Making the visibility protected or private will force the setting of values through the **Diskerror\Typed** mechanism. This mechanism silently ignores bad or unmapped property names and silently coerces input data into the best form represented by the property data type. Assigning the wrong data type to *public* properties will depend on the projects .

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

This method will return an array when *json_encode()* is called passing in the object. This will have the options to omit empty values and maintain expressions.

### _setBasicTypeAndConfirm

This static method references the input value and casts it to the basic requested type.

# TypedClass

The derivatives of **TypedClass** are contracted to do these things:

* Member/property access will behave like any standard PHP object, even when visibility set to "protected" or "private".
* Maintain the initial type of each member/property.
* Silently cast data assigned to non-public properties in the most obvious way when input member to *assign*, or *merge* is of a different type than the corresponding local property.
* Recognize classes inherited from **AtomicInterface** to manage their values internally.
* Handle special cases of members/properties that are objects with an option for handling NULL assignments.
* Implement “toArray” to return a deeply transformed standard associative array.
* Appropriately handle being passed to *serialize* and *json_encode*.
* Accept another object, associative or indexed array, and assign the input values to the appropriate members.
    * Copy each field or property item by item.
    * Copy indexed array by position.
    * Map alternate names to proper names.
    * Reset single property or entire object's members to their initValue values.

The users' class properties must be declared as "protected" or "private" for this contract to work. The names for the properties must follow the naming convention that the intended "public" members must not start with an underscore. This borrows from the Zend Framework property naming convention of protected and private property names starting with an underscore.

If a non-public property has no type then it is assumed to accept any type and null values. If a property has no type but has been assigned an initial value then the type will be assumed to be of the initial value.

Even more complex examples can be found int the "tests" directory.

# TypedArray

The instances or derivatives of **TypedArray** are contracted to do these things:

* Behave like a standard PHP associative array.
* Insure every member be the same type.
* Silently cast assigned data in the most obvious way.
* Implement “toArray” to return a deeply transformed PHP associative array.
* Handle the functions *serialize* and *json_encode* appropriately.

# DateTime and Date

These classes add convenience methods to the built-in PHP **DateTime** class. This includes the *__toString* method that returns a date-time string formatted for the initValue MySQL date-time format.

# SqlStatement

Utility class that outputs properly formatted partial SQL strings based on the input data. Both accept an input array, and an array where the values are the names of members to include from the first array. An empty array means to use all members.

## toInsert

Returns a string formatted for an SQL INSERT or UPDATE statement. Accepts an array where the values are the names of members to include. An empty array means to use all members.

## toValues

Returns a string formatted for an SQL "ON DUPLICATE KEY UPDATE" statement.

# Composer

```
> composer require diskerror/typed
```
