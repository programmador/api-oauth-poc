# Manual deploy

## Database

Fill database with fake users:

```
php bin/console doctrine:fixtures:load
```

# Testing

```
php vendor/bin/codecept build
php vendor/bin/codecept run api
```