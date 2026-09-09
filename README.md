# Local WordPress Environment

Docker Compose setup for running WordPress locally: WordPress + MySQL +
phpMyAdmin.

## Requirements

- Docker
- Docker Compose (bundled with Docker Desktop, or the `docker compose` plugin)

## Usage

```bash
# optional: copy and customize environment variables
cp .env.example .env

# start everything in the background
docker compose up -d

# view logs
docker compose logs -f

# stop everything
docker compose down

# stop and delete all data (database + WordPress files)
docker compose down -v
```

Once running:

- WordPress site: http://localhost:8080
- WordPress admin: http://localhost:8080/wp-admin
  - Username: `admin` (or `WORDPRESS_ADMIN_USER` from `.env`)
  - Password: `admin` (or `WORDPRESS_ADMIN_PASSWORD` from `.env`)
- phpMyAdmin: http://localhost:8081 (server: `db`, user/password from `.env`)

A `wpcli` service runs on startup and auto-provisions the admin account via
WP-CLI (`wp core install`), so there's no manual setup wizard to click
through. It exits once installation is done — `docker compose ps` will show
it as "Exited (0)", which is expected. Check its logs if the admin account
isn't ready yet: `docker compose logs wpcli`.

## Configuration

Copy `.env.example` to `.env` to override the defaults (database
credentials, exposed ports, admin username/password/email). If no `.env`
file is present, the defaults baked into `docker-compose.yml` are used —
**change the admin password before using this for anything beyond local
development.**

Data persists in named Docker volumes (`db_data`, `wp_data`) across restarts;
use `docker compose down -v` to wipe it.
