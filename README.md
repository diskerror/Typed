# Typed
PHP classes to strictly define member structure, to control their data types, and to add convience methods.

##TypedAbstract
This defines the convience methods that are to be implemented in the below classes.
###assignObject
Copies all matching member names while maintaining original types and doing a deep copy where appropriate.
This method silently ignores extra properties in the input object, leaves unmatched properties in the current class untouched, and skips names starting with an underscore (per Zend Framework coding style).
Input can be an object or an array.
###toArray
Returns an associative array of this object with only the appropriate members. A deep copy/converstion to an associative array from objects is also performed.

##TypedClass
The derivitives of Typed\TypedClass are contracted to do these things:
* Member/property access will behave like any standard PHP object.
* Maintain the initial type of each member/property.
* Silently cast data assigned to properties in the most obvious way when input is of a different type.
* Use setter methods based on property name to further handle input data, like filtering.
* Use getter methods based on property name to handle output, like formatting.
* Have a method to return a deeply transformed associative array.
* Handle special cases of members/properties that are objects.
* Accept a another object, associative or indexed array, and assign the input values to the appropriate members.
 *	Copy object or named array item by item.
 *	Copy indexed array by position.
 *  Map known erroneous names to proper names.
 *	Reset single property or entire object's members to their default values.

##TypedArray
The instances or derivitives of Typed\TypedArray are contracted to do these things:
* It will behave like a standard PHP associative array.
* Every member be the same type.
* Silently cast assigned data in the most obvious way when input is of a different type.
* Have a method to return a deeply transformed associative array.

##SqlStatement
Utility class that outputs properly formatted partial SQL strings based on the input data.
###getSqlInsert
Returns a string formatted for an SQL INSERT or UPDATE statement.
Accepts an array where the values are the names of members to include. An empty array means to use all members.
###getSqlValues
Returns a string formatted for an SQL "ON DUPLICATE KEY UPDATE" statement.
Accepts an array where the values are the names of members to include. An empty array means to use all members.

##Composer
```
	"repositories": [
		{
			"type": "package",
			"package": {
				"name": "diskerror/typed",
				"version": "dev-master",
				"source": {
					"url": "git://github.com/diskerror/typed.git",
					"type": "git",
					"reference": "master"
				}
			}
		}
	],
    "autoload": {
        "psr-4": {
        	"Diskerror\\Typed\\": ""
        }
    },
```