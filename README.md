# Typed
Classes to help manage data types of class members.
<p>The derivitives of Typed\Abstract will do these things:
<p>
<p>Maintain the initial type of each member property.
<p>Cast data assigned to properties in the most obvious way when input is a different type.
<p>Use setter methods based on property name to further handle input data, like filtering.
<p>Use getter methods based on property name to handle output, like formatting.
<p>Return a deeply transformed hashed array (or map).
<p>Handle special cases of object members.
<p>Accept a simple object, hashed array, or indexed array and assign the input values to the appropriate members.
<p>	Copy object or named array item by item.
<p>	Copy indexed array by position.
<p>	Accept JSON string.
<p>	Null and boolean false sets entire object's members to their default values.
<p>
<p>Typed\Array will have every member be the same type.
<p>Cast data assigned to indicies in the most obvious way when input is a different type.
<p>Return a deeply transformed hashed array (or map).
