<?php

use PHPUnit\Framework\TestCase;
#mdx:h use
use FlSouto\HtCheckb;
use FlSouto\Param;
#mdx:h al
require_once('vendor/autoload.php');

/* 
# HtCheckb

This library is used to create checkboxes.

## Installation

Use composer:

```
composer require flsouto/htcheckb
```

## Usage

The following example instantiates the checkbox class and renders it:

#mdx:Writable

Output:

#mdx:Writable -o httidy

The extra hidden field is a submit flag which indicates if the checkbox has been sent (i.e. the form has been submited). 
This is important in the case you have a checkbox that is checked by default but is unchecked by the user.
In case that a validation error occurs, the form must be shown again but with the checkbox unchecked (the default is ignored).

*/
class HtCheckbTest extends TestCase{

	function testWritable(){
		#mdx:Writable
		$checkbox = new HtCheckb("newsletter","Receive newsletter?");
		#/mdx echo $checkbox
		$output = $checkbox->__toString();
		$this->assertContains('label',$output);
		$this->assertContains('checkbox',$output);
		$this->assertContains('newsletter',$output);
		$this->assertContains('Receive newsletter',$output);
	}

/* 
The next example renders the checkbox in readonly mode (chekcbox button disabled):

#mdx:Readonly -php

Output:
#mdx:Readonly -o httidy

*/

	function testReadonly(){
		#mdx:Readonly
		$checkbox = new HtCheckb("newsletter");
		$checkbox->readonly();
		#/mdx echo $checkbox
		$output = $checkbox->__toString();
		$this->assertContains('checkbox',$output);
		$this->assertContains('disabled',$output);
		$this->assertContains('hidden',$output);
		$this->assertContains('newsletter',$output);

	}

/* 
Notice that a second hidden field is rendered as well. This is because when you disable a form field it will not be sent by browsers, and it is important to mantain the state of the data on a submit event even if the field is on readonly mode.

*/

/* 
### Changing the defaults

By default, the checkbox understands "1" as true and "0" as false. It also is unchecked (state "0") by default.
The below example changes all of that, so that 'true' means 'checked' and 'false' means unchecked. It also
makes the field checked by default:

#mdx:Fallback -php -h:al

Outputs:

#mdx:Fallback -o httidy

*/
	function testFallback(){
		#mdx:Fallback
		$checkbox = new HtCheckb('newsletter','Newsletter','true','false');
		$checkbox->fallback('true');
		#/mdx echo $checkbox
		$output = $checkbox->__toString();
		$this->assertContains('checked', $output);

	}

/* 
In the following example we are going to simulate a situation where the form is submited
(notice the presence of 'newsletter_submit' flag) but the checkbox key  (i.e. 'newsletter')
is not present. This means the user would have unchecked the checkbox that was checked by default.
So in this case the checkbox is rendered without the 'checked' attribute:

#mdx:Fallback2 -php -h:al,use

Outputs:

#mdx:Fallback2 -o httidy

*/

	function testFallbackNotWhenSubmited(){
		#mdx:Fallback2
		$checkbox = new HtCheckb('newsletter','Newsletter','true','false');
		$checkbox->fallback('true')->context(['newsletter_submit'=>1]);
		#/mdx echo $checkbox
		$output = $checkbox->__toString();
		$this->assertNotContains('checked', $output);
	}

/* 
Last but not least, I want to show you that the checkbox can also represent the state of a supposed
database row which uses the common Y/N pattern to indicate if it is active or not.
Notice that the checkbox is rendered unchecked even though it is told to be checked by default:

#mdx:Fallback3 idem

Output:

#mdx:Fallback3 -o httidy

*/
	function testFallbackNotWhenFieldIsSet(){
		#mdx:Fallback3
		// let's pretend this data was loaded from the database
		$row = ['active'=>'N'];

		$checkbox = new HtCheckb('active','Active','Y','N');
		$checkbox->fallback('Y'); // check by default
		$checkbox->context($row); // set the form's state
		#/mdx echo $checkbox
		$output = $checkbox->__toString();
		$this->assertNotContains('checked', $output);

	}

	function testExceptionThrownOnInvalidFallbackArgument(){
		$this->expectException(\Exception::class);
		$checkbox = new HtCheckb('newsletter','Newsletter','true','false');
		$checkbox->fallback(0);
	}


}