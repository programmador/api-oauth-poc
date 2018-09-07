# Manual deploy

## Database

Fill database with fake users:

```
php bin/console doctrine:fixtures:load
```

# Testing

Fill database with aforementioned user fixtures first!

```
php vendor/bin/codecept build
php vendor/bin/codecept run api
```