# Workplan Management API

Welcome to the Workplan Management API! This project is designed to manage employee types and other related functionalities. Below, you'll find detailed instructions on how to set up and run this project.

## Requirements

-   **PHP:** 8.2
-   **Laravel:** 11.x
-   **Database:** MySQL or any compatible SQL database

You can use any development environment like XAMPP, MAMP, or Laravel Valet for setting up your development environment.

## Getting Started

### Step 1: Clone the Repository

```bash
git clone https://github.com/your-username/employee-management-api.git
cd employee-management-api
```

### Step 2: Set Up Environment Variables

Copy the `.env.example` file to `.env` and update the environment variables to match your local setup.

```bash
cp .env.example .env
```

### Step 3: Install Dependencies

Install all the necessary dependencies using Composer.

```bash
composer install
```

### Step 4: Create the Database

Ensure your database is created. The database name should match the one in your `.env` file.

### Step 5: Run Migrations

Run the migrations to set up the database schema.

```bash
php artisan migrate
```

### Step 6: Seed the Database

Seed the database with initial data.

```bash
php artisan db:seed
```

### Step 7: Generate Application Key

Generate the application key.

```bash
php artisan key:generate
```

### Step 8: Serve the Application

Serve the application using the built-in Laravel development server.

```bash
php artisan serve
```

## Additional Information

### Running on Windows

For Windows users, we recommend using XAMPP or any other PHP environment of your choice. Ensure that PHP 8.2 is being used.

### Running on Mac

For Mac users, Laravel Valet is a great option for a local development environment. You can install it by following the [official documentation](https://laravel.com/docs/11.x/valet).

## API Endpoints

### Employee Types

-   **Get Employee Types**

    -   **Method:** GET
    -   **URL:** `/api/employee-types`
    -   **Params (optional):** `search` - String to search employee types by name

-   **Create Employee Type**

    -   **Method:** POST
    -   **URL:** `/api/employee-types`
    -   **Body:**
        ```json
        {
            "type": "permanent",
            "status": "active"
        }
        ```

-   **Update Employee Type**

    -   **Method:** PUT
    -   **URL:** `/api/employee-types/{id}`
    -   **Body:**
        ```json
        {
            "type": "contract",
            "status": "inactive"
        }
        ```

-   **Delete Employee Type**

    -   **Method:** DELETE
    -   **URL:** `/api/employee-types/{id}`

-   **Bulk Upload Employee Types**

    -   **Method:** POST
    -   **URL:** `/api/employee-types/bulk-upload`
    -   **Body:** Form-data or file input for uploading an Excel file

-   **Export Employee Types**
    -   **Method:** GET
    -   **URL:** `/api/employee-types/export`

## Contributing

If you'd like to contribute to this project, please fork the repository and use a feature branch. Pull requests are warmly welcome.

## License

This project is licensed under the MIT License.

---

Feel free to reach out if you have any questions or need further assistance. Happy coding!

```

This `README.md` provides a clear, step-by-step guide to setting up and running your Laravel project, along with some additional information on using different environments and the API endpoints.
```
