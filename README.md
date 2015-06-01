# Typed
PHP classes to help manage data types of class members.

##Typed\Abstract
The derivitives of Typed\Abstract will do these things:
* Member/property access will behave like any standard object.
* Maintain the initial type of each member/property.
* Silently cast data assigned to properties in the most obvious way when input is of a different type.
* Use setter methods based on property name to further handle input data, like filtering.
* Use getter methods based on property name to handle output, like formatting.
* Have a method to return a deeply transformed hashed array (or map).
* Handle special cases of members/properties that are objects.
* Accept a another object, hashed array, or indexed array and assign the input values to the appropriate members.
 *	Copy object or named array item by item.
 *	Copy indexed array by position.
 *	Accept JSON string and handle contents as the previous two type.
 *	Null and boolean false sets entire object's members to their default values.

##Typed\Array
The instances or derivitives of Typed\Array will do these things:
* It will behave like a standard PHP array.
* Every member be the same type.
* Silently cast assigned data in the most obvious way when input is of a different type.
* Have a method to return a deeply transformed hashed array (or map).
