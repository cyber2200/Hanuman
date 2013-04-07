Hanuman (POC)
=============

Code generation module and CMS for ZF2.

The main goal is to create a full CMS using strict MVC architecture.

Please let me know what you think and if you have any suggestions or you want to contribute.

How to install
==============

Create new directory, in this case "hanumanTest1":

* mkdir hanumanTest1

cd into the new dir: 

* cd ./hanumanTest1

Create new ZF2 skelton application in the new dir using git:

* git clone https://github.com/zendframework/ZendSkeletonApplication.git ./

* php composer.phar self-update

* php composer.phar install

Download and add the Hanuman module:

* git clone https://github.com/cyber2200/Hanuman.git ./module/Hanuman

Add the Hanuman module to the appication config:

* Open ./config/application.config.php

* Add the Hanuman module to the modules array so it will look as follow:
`````
'modules' => array(
	'Application',
	'Hanuman',
),
`````

Now, this is a code generator so we have to chnage the owner of the files to our web server user, in my case:

* sudo chown -R www-data ../*

Now you should be ready to use Hanuman:

* Browse the new module, for instance: http://localhost/hanumanTest1/public/Hanuman/
