# Typed
PHP classes to strictly define member structure, to control their data types, and to add convience methods.

It is currently in the process of being copied from [PHP Utilities] (https://github.com/diskerror/PHP-utilities#php-utilities) and might be incomplete.

##TypedAbstract
The derivitives of Typed\TypedAbstract will do these things:
* Member/property access will behave like any standard object.
* Maintain the initial type of each member/property.
* Silently cast data assigned to properties in the most obvious way when input is of a different type.
* Use setter methods based on property name to further handle input data, like filtering.
* Use getter methods based on property name to handle output, like formatting.
* Have a method to return a deeply transformed associative array (or map).
* Handle special cases of members/properties that are objects.
* Accept a another object, associative or indexed array, and assign the input values to the appropriate members.
 *	Copy object or named array item by item.
 *	Copy indexed array by position.
 *	Accept JSON string and handle contents as the previous two types.
 *	Reset entire object's members to their default values.

##TypedArray
The instances or derivitives of Typed\TypedArray will do these things:
* It will behave like a standard PHP array.
* Every member be the same type.
* Silently cast assigned data in the most obvious way when input is of a different type.
* Have a method to return a deeply transformed associative array (or map).

##TypedInterface
This defines the convience methods that are to be implemented in the above classes.
###assignObject
Copies all matching member names while maintaining original types and doing a deep copy where appropriate.
This method silently ignores extra properties in the input object, leaves unmatched properties in this class untouched, and skips names starting with an underscore.
Input can be an object, an associative array, or a JSON string representing a non-scalar type.
###toArray
Returns a simple array of this object with only the appropriate members. A deep copy/converstion to a simple array from objects is also performed.
###toJson
Returns JSON string representing the simple form (toArray) of this object. Optionally retruns a pretty-print string.
###getSqlInsert
Returns a string formatted for an SQL INSERT or UPDATE statement.
Accepts an array where the values are the names of members to include. An empty array means to use all members.
###getSqlValues
Returns a string formatted for an SQL "ON DUPLICATE KEY UPDATE" statement.
Accepts an array where the values are the names of members to include. An empty array means to use all members.
