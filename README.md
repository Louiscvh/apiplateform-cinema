# Installation of the Films and Genres API Application
This project consists of an API for managing films and genres. It offers the ability to add films and access an interface to view the full catalog.

## Prerequisites
1. Have Composer installed
2. Have Git installed
3. Have a MySQL database

## Installation Steps
Clone the project from Github by running the following command in your terminal:
```$ git clone https://github.com/Louiscvh/apiplateform-cinema.git```

Access the project folder by running the following command:
``` $ cd [the folder where your project is]```

Install the dependencies by running the following command:
```$ composer install```

Create a .env file from the .env.example file by running the following command:
```$ cp .env.example .env```

### Configure the database connection parameters in the .env file.

Create the database by running the following command:
```$ php bin/console doctrine:database:create```

Run the migrations by executing the following command:
```$ php bin/console doctrine:migrations:migrate```

Start the local server by executing the following command:
```$ php bin/console server:run```

### Using the application
Now that the application is installed and the server is running, you can access the EasyAdmin administration interface by accessing http://localhost:8000/admin in your browser. You will be able to add films and view the full catalog.

We hope you enjoy this application. Please feel free to provide any feedback or suggestions for improvement.