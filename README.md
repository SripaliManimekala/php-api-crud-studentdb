# php-api-crud-studentdb
CSC 469 2.0 mobile computing assignment

To run the backend:

Ensure that Docker is installed and running on the system.

Open VS Code and navigate to the directory containing the Docker Compose.yml file and PHP script.

Open a terminal in VS Code run the command `docker-compose up` to start the Docker containers(This will start both the Apache server (for the PHP scripts) and MySQL database container) defined in Docker Compose.yml file.

This command will start the containers defined in docker-compose.yml file. The PHP service will be accessible at http://localhost:9000

To run the frontend:

go to frontend in terminal and run the command `npm start `
