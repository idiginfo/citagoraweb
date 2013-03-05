Citagora Web Front-End Application
==================================

(c) 2012 Florida State University - All Rights Reserved


Installation
------------

0. Ensure Prerequisites:
   a. PHP v5.3+
   b. MongoDB
   c. PHP Mongo PECL Extension (v1.3+)
1. Extract all files to web accessible directory
2. Copy config/config.yml.dist to config/config.yml and adjust parameters
3. Run ./setup

Code Structure
--------------

The code is based off of some well-known, common PHP libraries:

 - The application framework is built using Silex (http://silex.com)
 - The Mongo and MySQL client libraries use Doctrine (http://doctrine.org)
 - The testing framework is PhpUnit (http://phpunit.de)
 - The dependency manager is Composer (http://getcomposer.com)
 - The HTML templating engine is Twig (http://twig.sensiolabs.org)

There are other libraries in use that come from Packagist.org.  Refer to the composer.json
file to see a list of dependencies.

The root folder is structured as follows:

/config   - Configuration files in YAML.  The local config file is not tracked with GIT
/console  - Contains the command-line executable for running the console interface
/src      - Contains the source code for the Citagora application
/tests    - Contains all of the unit tests for the Citagora
/vendor   - Third-party libraries automatically managed by Composer.
            * Do not modify the contents of this directory directly!
/web      - The public web folder.  Contains web interface, CSS, JS, etc.
            * This should be the DocumentRoot for whatever webserver software is being used

The code is structured into three sections:
 - Web:     Code that is specific to the web application
 - Cli:     Code that is specific to the command-line interface for the application
 - Commmon: These include common libraries that both the CLI and the Web interface use.

A detailed rundown of the sourcecode folder:

/Citagora
    App.php          The Silex App for the application.  Other App.php files extend this one.  Loads common libraries
    /Cli
        App.php      The Silex App for the CLI interface
        /Command     Contains all of the commands.  These are Symfony2 commands (refer to ______)
    /Common
        /BackendAPI    Abstraction API for things like user and document entities.  The controllers in the website interact with these
        /DataSource    Code for different datasources (MySQL, Mongo).  These are the actual workers for the BackendAPI
        /Model         Defines the models for users, documents, and other entities used in Citagora
        /SilexProvider Helper classes that make it easier to load resources into Silex
    /Web
        App.php        The Silex App for the Web interface
        /Controller    Contain all of the controllers for the website.  URLs map to methods in these classes
        /Form          Contain all of the form classes.  These are Symfony2 forms
        /Library       Contain web-specific libraries, such as Account management, etc.
        /Model         Contain web-specific models, such as DocumentSearchRequest
        /Oauth         Contain Oauth client classes
        /SilexProvider Helper classes that make it easier to load resources into Silex
        /Views         All of the Twig views