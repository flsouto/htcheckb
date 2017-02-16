# HtCheckb

This library is used to create checkboxes.

## Installation

Use composer:

```
composer require flsouto/htcheckb
```

## Usage

The following example instantiates the checkbox class and renders it:

```php
<?php
use FlSouto\HtCheckb;
require_once('vendor/autoload.php');

$checkbox = new HtCheckb("newsletter","Receive newsletter?");

echo $checkbox;
```

Output:

```html

<div class="widget 58a3990befc0e" style="display:block">
 <label style="display:block" for="58a3990befc0e">
 <input name="newsletter" type="checkbox" value="1" /> Receive newsletter?</label>
 <input type="hidden" name="newsletter_submit" value="1" />
</div>

```

The extra hidden field is a submit flag which indicates if the checkbox has been sent (i.e. the form has been submited). 
This is important in the case you have a checkbox that is checked by default but is unchecked by the user.
In case that a validation error occurs, the form must be shown again but with the checkbox unchecked (the default is ignored).


The next example renders the checkbox in readonly mode (chekcbox button disabled):

```php
use FlSouto\HtCheckb;
require_once('vendor/autoload.php');

$checkbox = new HtCheckb("newsletter");
$checkbox->readonly();

echo $checkbox;
```

Output:
```html

<div class="widget 58a3990bf1a20" style="display:block">
 <label style="display:block" for="58a3990bf1a20">
 <input type="checkbox" disabled="disabled" /> Newsletter</label>
 <input type="hidden" name="newsletter" value="0" />
 <input type="hidden" name="newsletter_submit" value="1" />
</div>

```


Notice that a second hidden field is rendered as well. This is because when you disable a form field it will not be sent by browsers, and it is important to mantain the state of the data on a submit event even if the field is on readonly mode.


### Changing the defaults

By default, the checkbox understands "1" as true and "0" as false. It also is unchecked (state "0") by default.
The below example changes all of that, so that 'true' means 'checked' and 'false' means unchecked. It also
makes the field checked by default:

```php
use FlSouto\HtCheckb;

$checkbox = new HtCheckb('newsletter','Newsletter','true','false');
$checkbox->fallback('true');

echo $checkbox;
```

Outputs:

```html

<div class="widget 58a3990bf213b" style="display:block">
 <label style="display:block" for="58a3990bf213b">
 <input name="newsletter" type="checkbox" value="true" checked="checked" /> Newsletter</label>
 <input type="hidden" name="newsletter_submit" value="1" />
</div>

```


In the following example we are going to simulate a situation where the form is submited
(notice the presence of 'newsletter_submit' flag) but the checkbox key  (i.e. 'newsletter')
is not present. This means the user would have unchecked the checkbox that was checked by default.
So in this case the checkbox is rendered without the 'checked' attribute:

```php

$checkbox = new HtCheckb('newsletter','Newsletter','true','false');
$checkbox->fallback('true')->context(['newsletter_submit'=>1]);

echo $checkbox;
```

Outputs:

```html

<div class="widget 58a3990bf2758" style="display:block">
 <label style="display:block" for="58a3990bf2758">
 <input name="newsletter" type="checkbox" value="true" /> Newsletter</label>
 <input type="hidden" name="newsletter_submit" value="1" />
</div>

```


Last but not least, I want to show you that the checkbox can also represent the state of a supposed
database row which uses the common Y/N pattern to indicate if it is active or not.
Notice that the checkbox is rendered unchecked even though it is told to be checked by default:

```php

// let's pretend this data was loaded from the database
$row = ['active'=>'N'];

$checkbox = new HtCheckb('active','Active','Y','N');
$checkbox->fallback('Y'); // check by default
$checkbox->context($row); // set the form's state

echo $checkbox;
```

Output:

```html

<div class="widget 58a3990bf2d4b" style="display:block">
 <label style="display:block" for="58a3990bf2d4b">
 <input name="active" type="checkbox" value="Y" /> Active</label>
 <input type="hidden" name="active_submit" value="1" />
</div>

```
