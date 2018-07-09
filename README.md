# Typed
PHP classes to strictly define member structure, to control their data types, and to add convenience methods. The master branch is considered to be in constant development. Once stability is achieved it is then tagged.

## TypedInterface
This declares the convenience methods that are to be implemented in the classes below.

### assign
Copies all matching member names while maintaining original types and doing a deep copy where appropriate. This method silently ignores extra properties in the input object, leaves unmatched properties in the current class untouched, and skips names starting with an underscore (per Zend Framework coding style).

Input can be an object or an array. A NULL or FALSE will set the *Typed* object to it's default values.

### toArray
Returns an associative array of this object with only the appropriate members. A deep copy/conversion to an associative array from objects is also performed. Options can be set for the returned array and are stored in the *ArrayOptions* class. The top level setting will override each nested object of type *Typed*, though nested objects will retain their original settings.

* OMIT_EMPTY: null or empty members are omitted to shrink storage or transmission needs.
* OMIT_RESOURCE: resource IDs are meaningless for transmitted data.
* SWITCH_ID: a top level member with the name "id_" is assumed to be intended to be a Mongo primary key and the name is changed to "_id";
* KEEP_JSON_EXPR: objects of type *Zend\Json\Expr* remain untouched;
* TO_BSON_DATE: conversion of all objects with a *DateTime* lineage to *MongoDB\BSON\UTCDateTime* with all times assumed to be UTC, *MongoDB\BSON\UTCDateTime* objects will remain untouched;

## TypedClass
The derivitives of *Typed\TypedClass* are contracted to do these things:
* Member/property access will behave like any standard PHP object.
* Maintain the initial type of each member/property.
* Silently cast data assigned to properties in the most obvious way when input is of a different type.
* Use setter methods based on property name to further handle input data, like filtering.
* Use getter methods based on property name to handle output, like formatting.
* Impliment “toArray” to return a deeply transformed standard associative array.
* Handle special cases of members/properties that are objects with an option for handling NULL assignments.
* Accept another object, associative or indexed array, and assign the input values to the appropriate members.
  * Copy (clone) object or named array item by item.
  * Copy indexed array by position.
  * Map alternate names to proper names.
  * Reset single property or entire object's members to their default values.

## TypedArray
The instances or derivatives of *Typed\TypedArray* are contracted to do these things:
* It will otherwise behave like a standard PHP associative array.
* Every member be the same type.
* Silently cast assigned data in the most obvious way when input is of a different type.
* Impliment “toArray” to return a deeply transformed associative array.

# Classes for autoload
These next two classes are best thought of as namespaces of functions. Class design is used to activate the autoload feature of PHP.

## Cast
*Cast* contains methods to return the input cast to the basic scalars or an array.

## SqlStatement
Utility class that outputs properly formatted partial SQL strings based on the input data.
### getSqlInsert
Returns a string formatted for an SQL INSERT or UPDATE statement.
Accepts an array where the values are the names of members to include. An empty array means to use all members.
### getSqlValues
Returns a string formatted for an SQL "ON DUPLICATE KEY UPDATE" statement.
Accepts an array where the values are the names of members to include. An empty array means to use all members.

# Composer
```
> composer require diskerror/typed
```
