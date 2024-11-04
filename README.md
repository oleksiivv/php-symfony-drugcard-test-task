# Instruction

Follow these steps to set up the project:

1. Install dependencies:
   ```
   composer install
   ```
2. Execute database migrations:
   ```
   php bin/console doctrine:migrations:migrate
   ```
3. For command execution with CSV save strategy, execute the following command after consuming messages:
   ```
   php bin/console messenger:consume async -vv
   ```

# Command Signature

```
app:parse-products store storage
```

## Store Options

Refer to `App\Enum\ProductsStoreEnum` for possible store sources:

- rozetka
- moyo
- foxtrot
- all (default)

## Output Options

Refer to `App\Enum\StorageTypeEnum` for output formats:

- csv
- db
- all (default)

## API Endpoint

**GET** `/product/`
- **Query Parameter**:
  - `storageType` (string, optional, values: csv/db)

## Unit Tests

Test coverage includes:

- Namespaces:
  - `App\System\Repository\`
  - `App\System\Service\`

**Execute Tests:**
   ```
   php bin/phpunit
   ```

## Docker Usage

### Running the Server

1. Build and run the Docker container:
   ```
   sudo docker-compose up -d --build
   ```
2. Access the application at:
   ```
   http://127.0.0.1:8080/product/
   ```

### Executing Tests in Docker Container

1. Enter the Docker container:
   ```
   docker compose exec web sh
   ```
2. Execute the PHP unit tests:
   ```
   php bin/phpunit
   ```
3. *Follow the same process for other commands.*
