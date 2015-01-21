# Wizacha - Discuss #

Small library to manage some discussions.


## Installation ##

Add following requirement in your `composer.json` file:
```
#!json
{
    "require" : {
        "wizacha/discuss": "*"
    }
}
```

To initialize underlying database, you need to create file `config\discuss.config.php` returning
an array with connexion parameters. Here an example for a *mysql* connection:

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

Then you can use following commands:

* `php vendor/bin/discuss create` To create database tables (only if not previously created).
* `php vendor/bin/discuss update` To create or update the database tables.
* `php vendor/bin/discuss drop` Drop all tables related to discuss.

## Usage ##

To start, you will have to create a `Wizacha\Discuss\Client` instance.
You can easily use the `config\discuss.config.php` file created earlier.
```
#!php
<?php
$discuss = new \Wizacha\Discuss\Client(
    include($path . '/config/discuss.config.php')
);
```

## Development ##

### Doctrine ###

To use Doctrine CLI, you also need to create file `config\discuss.config.php`.

For more documentation about drivers and related parameters, check
[doctrine documentation](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html).

Here some useful commands:

* `php vendor/bin/doctrine help` Displays help for a command (?)
* `php vendor/bin/doctrine orm:schema-tool:update` Processes the schema and either update the database schema of EntityManager Storage Connection or generate the SQL output.
* `php vendor/bin/doctrine orm:schema-tool:drop` Processes the schema and either drop the database schema of EntityManager Storage Connection or generate the SQL output.

For more documentation about doctrine-cli, check
[corresponding documentation](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/tools.html).

**Note:** For *usual* commands above, you can use the alias `bin/discuss`.
          
### Tests ###

All tests must be written under `tests` directory, with a path matching the class namespace
relatively to `Wizacha\Discuss`. For example, the tests of `Wizacha\Discuss\Entity\Message.php`
are written in file `tests\Entity\Message.php`.

To run the tests: `composer run-tests`

