# Wizacha - Discuss #

Small library to manage some discussions.


## Installation ##

Add following requirement in your `composer.json` file:
*TODO*

## Usage ##
*TODO*

## Development ##

### Doctrine ###
If you want to use the doctrine CLI, you need to create file `config\local.php` returning
an array with doctrine connexion parameters. Here an example for a *mysql* connection:

```
#!php
<?php
return [
    'driver'   => 'pdo_mysql',
    'host'     => '...',
    'user'     => '...',
    'password' => '...',
    'dbname'   => '...',
];
```

For more documentation about drivers and related parameters, check
[doctrine documentation](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html).

Here some useful commands:

* `php vendor/bin/doctrine help` Displays help for a command (?)
* `php vendor/bin/doctrine orm:schema-tool:update` Processes the schema and either update the database schema of EntityManager Storage Connection or generate the SQL output.
* `php vendor/bin/doctrine orm:schema-tool:drop` Processes the schema and either drop the database schema of EntityManager Storage Connection or generate the SQL output.

For more documentation about doctrine-cli, check
[corresponding documentation](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/tools.html).
          
### Tests ###

All tests must be written under `tests` directory, with a path matching the class namespace
relatively to `Wizacha`. For example, the tests of `Wizacha\Discuss\Entity\Message.php`
are written in file `tests\Discuss\Entity\Message.php`.

To run the tests: `composer run-tests`

