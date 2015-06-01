# Typed
PHP classes to help manage data types of class members.
<p>The derivitives of Typed\Abstract will do these things:

* Maintain the initial type of each member property.
* Cast data assigned to properties in the most obvious way when input is a different type.
* Use setter methods based on property name to further handle input data, like filtering.
* Use getter methods based on property name to handle output, like formatting.
* Return a deeply transformed hashed array (or map).
* Handle special cases of object members.
* Accept a simple object, hashed array, or indexed array and assign the input values to the appropriate members.
 *	Copy object or named array item by item.
 *	Copy indexed array by position.
 *	Accept JSON string.
 *	Null and boolean false sets entire object's members to their default values.

<p>The derivitives of Typed\Array will do these things:
* Every member be the same type.
* Cast data assigned to indicies in the most obvious way when input is a different type.
* Return a deeply transformed hashed array (or map).
