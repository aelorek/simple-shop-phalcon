# Recruitment task - Krzysztof F.

## Requirements
- PHP7.1
- Composer


## Configutaion

Copy app/config/config.sample.php to app/config/config.php and configure database user, password, dbame

Execute the contents of the files in the ```./database/``` directory in the database in the following order
```
product_structure.sql
user_structure.sql
user_data.sql
```


## Testing
Install packages using composer
```
$ composer install
```


Copy app/config/config.sample.php to app/config/config.test.php and configure database user, password, dbame for testing

Run phpunit
```
$ ./vendor/bin/phpunit ./tests/
```