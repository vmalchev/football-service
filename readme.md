# Football API

## Requirements

* Git
* Docker
* Connection to an Enetpulse MySQL database, which is maintained by the sportal/enetpulse-importer project.

## Installation

1. Clone latest Git master
    
    ```bash
    git clone https://{username}]@bitbucket.org/sportal-media-platform/football-api.git
    cd football-api
    ```

    For SSH cloning (add SSH keys first)
    ```bash
    git clone git@bitbucket.org:sportal-media-platform/football-api.git
    ```

2. Setup environment

    ```bash
    cp .env.example .env
    ```
    
3. Manage containers
    
    Start container
    ```bash
    ./manage.sh start
    ```

    Execute commands inside running container
    ```bash
    ./manage.sh exec
    ```

    Stop container
    ```bash
    ./manage.sh stop
    ```
     
4. Restore/Migrate Database
    
    Once started, the container exposes postgresql on port 5432, you need to connect from the host and restore a database.

    Copy database to container (dump of latest db can be found on: https://console.cloud.google.com/storage/browser/smp-sql-exports;tab=objects?project=mythic-producer-212107&prefix=&forceOnObjectsSortingFiltering=false)
    ```bash
    docker cp <sql-database-dump> football-api-postgres-db-1:/<sql-database-dump>
    ```

    Get inside container
    ```bash
    ./manage.sh exec postgres-db
    ```

    Connect to PostgreSQL database
    ```bash
    psql -h 127.0.0.1 -U postgres
    ```

    Create database in PostgreSQL
    ```bash
    create database football_api;
    ```

    Exit from Postgresql
    ```bash
    \q
    ```

    Import database file via `psql`
    ```bash
    psql -U postgres football_api < <sql-database-dump>
    ```


5. Install Laravel dependencies and create migration files

    Once the container is running install dependencies with:
    ```bash
    ./manage.sh exec php composer install
    ```

    And create migrations with:
    ```bash
    ./manage.sh exec php php artisan migrate
    ```

5. Setup cron jobs

    Add the following cron entry

    ```
    * * * * * php /path/to/football-api/artisan schedule:run >> /dev/null 2>&1
    ```
    
6. Running background task queues

    The following command needs to be run as a daemon to process background tasks.
    ```
    php /path/to/football-api/artisan queue:work --daemon
    ```

## Xdebug setup

### Required IDE plugins: 

* PHP Remote Interpreter
* PHP Docker

### IDE settings

Languages and Frameworks | PHP

* On the **PHP** page, click the Browse button next to the **CLI Interpreter** list.
* In the **CLI Interpreters** dialog that opens, click the Add button in the left-hand pane, then choose **From Docker, Vagrant, VM, WSL, Remote...** from the popup menu.
* In the **Configure Remote PHP Interpreter** dialog that opens, choose the **Docker** method.
* Create new docker configuration with default settings (**Server** field).
* Select `football-api_php:latest` in the **Image name** field and press OK.
* Verify that PHP and Xdebug version are successfully retrieved on the **CLI Interpreters** dialog.

Languages and Frameworks | PHP | Debug

* Verify that Xdebug's **Debug port** is set to `9003` and **Can accept external connections** checkbox is checked.

Languages and Frameworks | PHP | Servers

* Create new `football-api` server (the server name must match the `PHP_IDE_CONFIG` env variable).
* Use `localhost` as **Host**, `80` as **Port** and Xdebug as **Debugger**.
* Check the **Use external path mappings** checkbox and map the local football-api directory to the container's workdir `/var/www/html`

### Enabling Xdebug for artisan commands

In order to enable Xdebug when running artisan commands, include the Xdebug options as command arguments and enable IDE debug listener.
Example: `php -d xdebug.mode=debug -d xdebug.client_host={host_machine_ip} -d xdebug.start_with_request=yes {artisan command}`


## Further reading

  - [Create And Restore PostgreSQL Backups](https://docs.bitnami.com/installer/infrastructure/mapp/administration/backup-restore-postgresql/)
  - [psql documentation](https://www.postgresql.org/docs/devel/app-psql.html)
  - [Configure SSH and two-step verification](https://support.atlassian.com/bitbucket-cloud/docs/configure-ssh-and-two-step-verification/)
  - [Laravel: Running Migrations](https://laravel.com/docs/7.x/migrations#running-migrations)