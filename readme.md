# Run this project

#### 1. Clone this project from github :
`git@github.com:rafsanhasin/booking_system.git`

#### 2. Configure docker compose :
`docker-compose.yml`

#### 3. Build and run docker :
 `docker-compose build && docker-compose up -d`

#### 4. Configure :
 `.env`

#### 5. Update dependencies :
  `composer update`

#### 6. To create DB: 
`php bin/console doctrine:database:create`

#### 7. To migrate from Entity : 
`php bin/console doctrine:schema:update --force`

#### 8. Seed predefined admin user and rooms : 
`php bin/console doctrine:fixtures:load`