# Car Collection Management

## Prerequisites

- PHP 8.2
- Composer
- Symfony CLI
- A database (MySQL)

## Installation

1. **Clone the repository**
    
   ```bash
   git clone https://github.com/GeoffreyBidi/arengi_technical_test.git
    cd arengi_technical_test
    ```

2. **Install dependencies**
    
   ```bash
   composer install
   ```

3. **Configure environment variables**

   Copy the `.env` file and rename it to `.env.local`, then modify the values of the environment variables according to your configuration.

4. **Create the database**
    
   ```bash
   php bin/console doctrine:database:create
   ```

5. **Run migrations**

    ```bash
    php bin/console doctrine:migrations:migrate
    ```

6. **Load fixtures (test data)**

    ```bash
    php bin/console doctrine:fixtures:load
    ```

7. **Start the development server**

    ```bash
    symfony server:start
    ```
   The project will be accessible at `http://localhost:8000`.

## Improvements

- Refactor Enum PHP native type to Doctrine Enum Type
- Dockerize the application
- Add CI/CD pipeline
