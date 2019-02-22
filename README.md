# Typed
PHP classes to strictly define member structure, to control their data types, and to add convenience methods. The master branch is considered to be in constant development. Once stability is achieved it is then tagged.

## TypedAbstract
This declares the convenience methods that are to be implemented in the classes below.

### assign
Copies all matching member names while maintaining original types and doing a deep copy where appropriate. This method silently ignores extra properties in the input object, returns unmatched properties in the current TypedClass child to their default values or removes the previous values from TypedArray children, and skips names starting with an underscore (per Zend Framework coding style). EXCEPT: the property name "_id" is allowed for use with MongoDB.

Input can be an object or an array. A NULL or FALSE will set the *Typed* object to it's default values.

### replace
This method is similar to the "assign" method except that unmatched keys from the input are left untouched.

### merge
This method is similar to "replace" above except that it clones the current object and then replaces matching values with the input values and returns the new Typed object.

### toArray
Returns an associative array of this object with only the appropriate members. A deep copy/conversion to an associative array from objects is also performed. Options can be set for the returned array and are stored in the *ArrayOptions* class. The top level setting will override each nested object of type *Typed*, though nested objects will retain their original settings.

* OMIT\_EMPTY: null or empty members are omitted to shrink storage or transmission needs.
* OMIT\_RESOURCE: resource IDs are meaningless for transmitted data.
* KEEP\_JSON\_EXPR: objects of type *Zend\Json\Expr* remain untouched.

The following are defined here but are only used in the [project defining the MongoDB\BSON](https://github.com/diskerror/TypedBSON) support.

* NO\_CAST\_BSON: Setting this will instruct the conversion to an array to leave *MongoDB\BSON* instances alone.
* CAST\_DATETIME\_TO\_BSON: Cast all *DateTimeInterface* objects to *MongoDB\BSON\UTCDateTime* objects.
* CAST\_ID\_TO\_OBJECTID: Cast member with the name “_id” into *MongoDB\BSON\ObjectId* when performing “bsonSerialize()”.

### getArrayOptions & setArrayOptions
These manage the usage of the options for how these classes are converted to an array.

### Serialization

*TypedInterface* extends the builtin *Serializable* and *JsonSerializable* classes which *TypedArray* and *TypedClass* both implement.

The *Serializable* classes are coded to store the minimum data required to fully rebuild the data classes, and make use of extensive self-checking in the *Typed* classes. Many of the housekeeping properties are not serialized.

The implementations of *JsonSerializable* and *Persistable* only return the user defined members. A *Typed* class or array can be reconstituted by passing the JSON string to the appropriate constructor. The options for converting these classes to an array will then be the default values.

**NOTE:** Usage of *Persistable* and BSON data conversion have been split off to a new [project](https://github.com/diskerror/TypedBSON) so that *Typed* can be used without being forced to install the MongoDB extensions and frameworks when they are not being used.

## TypedClass
The derivitives of *TypedClass* are contracted to do these things:
* Member/property access will behave like any standard PHP object.
* Maintain the initial type of each member/property.
* Silently cast data assigned to properties in the most obvious way when input is of a different type.
* Use setter methods based on property name to further handle input data, like filtering.
* Use getter methods based on property name to handle output, like formatting.
* Recognize classes inhearited from *ScalarAbstract* to manage their values internally.
* Handle special cases of members/properties that are objects with an option for handling NULL assignments.
* Impliment “toArray” to return a deeply transformed standard associative array.
* Accept another object, associative or indexed array, and assign the input values to the appropriate members.
  * Copy each field or property item by item.
  * Copy indexed array by position.
  * Map alternate names to proper names.
  * Reset single property or entire object's members to their default values.

The users' class properties must be declared as *protected* or *private*. The names for the properties must follow the naming convention that the intended *public* members must not start with an underscore. This borrows from the Zend Framework property naming convention of protected and private property names starting with an underscore.

These properties must also be initialized with a value. Each values' initial type is stored within the object and used to cast new values to the same type.

More complex types can be set with array notation as such:
```
class MyClass extends Diskerror\Typed\TypedClass
{
    protected $myDate = [DateTime::class, 'now'];
    protected $myDateOpt = ['DateTime', 'now'];  // Optional form.
    protected $myString = 'default value';
}
```
The first member of the array is the name of the class and the remaining members become the parameters passed to the class constructor.

Even more complex examples can be found int the "tests" directory.

## TypedArray
The instances or derivatives of *TypedArray* are contracted to do these things:
* It will otherwise behave like a standard PHP associative array.
* Every member be the same type.
* Silently cast assigned data in the most obvious way when input is of a different type.
* Impliment “toArray” to return a deeply transformed PHP associative array.

## DateTime and Date
These two classes have been moved from [Utilities](https://github.com/diskerror/Utilities) and that repository is now considered obsolete. These classes add convenience methods to the built-in PHP *DateTime* class. This includes the *__toString* method that returns a date-time string formatted for the default MySQL date-time format.

# Classes for autoload
This class is best thought of as namespaces of functions. Class design is used to activate the autoload feature of PHP.

## SqlStatement
Utility class that outputs properly formatted partial SQL strings based on the input data. Both accept an input array, and an array where the values are the names of members to include from the first array. An empty array means to use all members.
### toInsert
Returns a string formatted for an SQL INSERT or UPDATE statement.
Accepts an array where the values are the names of members to include. An empty array means to use all members.
### toValues
Returns a string formatted for an SQL "ON DUPLICATE KEY UPDATE" statement.


# Composer
```
> composer require diskerror/typed
```
