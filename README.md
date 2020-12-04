# Test task

Test task is Symfony application which is developed as part of recruitment test

## Installation

Run composer install

```bash
composer install
```

Copy .env file in .env.local and modify values for database and Redis

Create database and run migrations

 ```bash
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
```

For local environment you can use Symfony server 
 ```bash
symfony server:start
```

## Documentation

API documentation is available on /docs

## Future work
* Add missing tests for GameHelperService and RedisService
* Add more functional tests for all existing endpoints
* Create some sort of pagination for game history endpoint 
* Test Redis caching with large amount of data and modify if necessary
* Add more possible content types for both requests and responses (currently only JSON is supported)
