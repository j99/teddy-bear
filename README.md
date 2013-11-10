# Teddy-Bear
A simple, secure and extendible two-part php encryption system. (Each file is fully documented)

## How it works
Teddy-Bear is a combination of two classes, `Teddy` and `Bear`. The first, `Teddy`, is the actual encryption system. The second, `Bear`, is where the `key` and `iv` are stored.

## Installation
Install Teddy-Bear only requires two lines of code.
```php
<?php
require 'path/to/classes/bear.php';
require 'path/to/classes/teddy.php';
// ...other code...
```
It is **very** important to require `bear.php` first, because it is used by `teddy.php`

## Usage
Using Teddy-Bear is very easy, only a couple lines of code.

### Basic Usage
The basic usage is just using the built in classes and not extending them for encryption and decryption.
```php
<?php
require 'path/to/classes/bear.php';
require 'path/to/classes/teddy.php';

$privatekey = '@username+time_joined+user_id';
$ted = new Teddy(md5($privatekey)); // create instance of Teddy

$encrypted_str = $ted->encrypt('this is my super secret text string.');
echo $encrypted_str; // encrypted string

$decrypted_str = $ted->decrypt($encrypted_str);
echo $decrypted_str; // decrypted string / original string
```
If you only use the methods above, you don't even notice `Bear`. The basic usage allows for simple two-way encryption and decryption.

### Using the same IV and Key
If you would like to re-use or use the `iv` and `key` in the same place. You have to change your code a bit.

File: `start.php`
```php
<?php
require 'path/to/classes/bear.php';
require 'path/to/classes/teddy.php';

$privatekey = 'app_id+app_secret+orginization';
$global_ted = new Teddy(md5($privatekey));
$bear_instance = $global_ted->get(); // store this somewhere
```
The first file you could define a new `iv` and `key` by initializing `Teddy` and then storing the `Bear` instance globally.

File: `use.php`
```php
<?php
$b = get_global_bear_instance_from_somewhere();

$ted = new Teddy($b); // instead of passing a key, you pass it a Bear instance containing the IV and KEY.
$encrypted_str = $ted->encrypt('some other encrypted string.');
echo $encrypted_str; // encrypted string
```
Now, wherever we use the same `Bear` we will get the same results. So you can simply encrypt some text in one file, store the `Bear` instance, and then in another file create a new `Teddy` instance using that `Bear` instance and decrypt that text.

## Extending Teddy-Bear
If you want your own encryption system, and don't want to use `Teddy`, you have two options: sub-class `Teddy` or create your own encryption class. Both are very easy, refer to [the docs](https://github.com/j99/teddy-bear/blob/master/api.md) for what methods to implement.

## License
Teddy-Bear is licensed under the [MIT license](https://github.com/j99/teddy-bear/blob/master/LICENSE).
