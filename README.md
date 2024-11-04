# Command Signature

```
app:parse-products web-store app-storage
```

## Store Options

Refer to `App\Enum\ProductsStoreEnum`:

- rozetka
- moyo
- foxtrot
- all (default)

## Output Options

Refer to `App\Enum\StorageTypeEnum`:

- csv
- db
- all (default)

## API Endpoint

**GET** `/product/`
- **Query parameter**:
  - `storageType` (string, optional, values: csv/db)

## Unit Tests

Coverage for the following namespaces:
- `App\System\Repository\`
- `App\System\Service\`

**Execute Tests:**
```
php bin/phpunit
```

## Docker Usage

### Run Server

1. Build and run the docker container:
   ```
   sudo docker-compose up -d --build
   ```
2. Access the application at:
   ```
   http://127.0.0.1:8080/product/
   ```

### Execute Tests in Docker Container

1. Enter the container:
   ```
   docker compose exec web sh
   ```
2. Run the tests:
   ```
   php bin/phpunit
   ```
