# HTMLGenerator

## Description
A simple HTML generator written in PHP. It provides several PHP classes to build correctly your HTML tags in regard of W3C standards.
The HTML can either be generated indented or not.

##Files

### HTMLGenerator.php
Main PHP file, provide the basic classes and abstract classes to generate HTML.

#### HTMLGenerator.php classes

##### HTMLElement
Abstract interface for all HTML elements.

###### Methods
- toString() : output the element as plain HTML, not indented
- toIndentedString() : output the element as indented HTML

- isScalar() : util method used to know if the HTML element is a container (may contain sub element) or a scalar element (cannot contain any sub element)

##### HTMLRawText
Encapsulates raw text in order to add it to another HTML elements. Pure raw text, such as "Hello world !"

###### Methods
- toString() : output the text
- toIndentedString() : output the text, same as toString()

- isScalar() : return true

##### HTMLTag
Abstract class for HTML tags.

###### Methods
- hasAttribute($name) : return true if the attribute $name is set.
- getAttribute($name) : return the attribute value. Return null is atribute is missing.
- setAttribute($name, $value=null) : create or modify an HTML attribute. If value is not null, the attribute will be outputed like 'name="value"', else, the attribute will be outputed like 'name'. null value is used for the "checked" attribute for example.
- removeAttribute($name) : remove the attribute $name
- removeAllAttributes() : remove all attributes

- hasId() : return true if the 'id' attribute is set
- getId() : return the value of the 'id' attribute; return null is the attribute is not set
- setId($id) : set the 'id' attribute of the tag

- hasClass($name) : return true if the tag has the class $name. HTML classes is considered as a set of classes. The tag may have more than one class.
- addClass($name) : add the class $name to the set of classes of the tag. The classes will be outputed as a tag attribute 'class'. If the tag has, for example, two classes 'one' and 'two', the class attribute will be outputed as 'class="one two"', each class separated by a space character.
- setClass($classes) : set the whole class 'attribute'. The parameter $classes has to be of string type.
- setClasses($classes) : set the whole class 'attribute'. The parameter $classes has to be of String Array type. Each entry of the array must be a different classname.
- getClasses() : return the array of classes of the tag.
- removeClass($class) : remove the class $class from the class array.
- removeAllClasses() : remove all classes of the tag and unset the 'class' attribute.

##### HTMLSimpleTag
Class for auto-closed HTML tags. HTMLSimpleTag are scalar, the cannot contain sub elements. HTML inputs are HTMLSimpleTag.

###### Methods
- toString() : output the tag, not indented
- toIndentedString() : output the tag, indented
- isScalar() : return true

##### HTMLContainer
Class for container HTML tags. It can contain sub elements. HTMLContainer is closed by a closing tag. Example <tag></tag>.

###### Methods
- addChild($child) : add sub element the the contianer
- insertChildAt($index, $child) : insert a sub element at the given index. If the $index is < 0, the sub element is inserted at first slot. If the $index is superior to the current maximum index, the sub element is inserted at the last slot.
- getChildById($id) : returns the first child element with the attribute 'id' equal to $id. Returns null if not found.
- getChildAt($index) : returns the element located at this index. Returns null if not existing.
- getFirstChild() : return the first sub element. Null if there is no element.
- getLastChild() : return the last sub element. Null if there is no element.
- removeAllChilds() : remove all sub elements.

- addRawText($text) : inserts raw text sub element. The raw text can be inserted between two other sub elements. Example : <a><b/>sub raw text<c></c></a>
- setInnerHTML($text) : replace all previously set content (sub elements) by the raw text $text.
- setText($text) : alias for setInnerHTML

- isScalar() : returns false