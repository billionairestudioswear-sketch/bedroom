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

- WordPress: http://localhost:8080
- phpMyAdmin: http://localhost:8081 (server: `db`, user/password from `.env`)

On first visit to WordPress, you'll be walked through the standard install
wizard (site title, admin user, password).

## Configuration

Copy `.env.example` to `.env` to override the defaults (database
credentials, exposed ports). If no `.env` file is present, the defaults baked
into `docker-compose.yml` are used.

Data persists in named Docker volumes (`db_data`, `wp_data`) across restarts;
use `docker compose down -v` to wipe it.
