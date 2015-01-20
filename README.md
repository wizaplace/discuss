# Wizacha - Discuss #

Small library to manage some discussions.


### Installation ###

Add following requirement in your `composer.json` file:
*TODO*

### Usage ###
*TODO*

### Development ###
If you want to use the doctrine CLI, you need to create file `config\local.php` returning
an array with doctrine connexion parameters. Here an example for a *mysql* connection:

```
#!php
<?php
return [
    'driver'   => 'pdo_mysql',
    'user'     => '...',
    'password' => '...',
    'dbname'   => '...',
];
```

For more documentation about drivers and related parameters, check
[doctrine documentation](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html).
          
### Tests ###
*TODO*
