## Description
This is sample framwork created for practice. It follows course [Let's Build A Forum with Laravel and TDD](https://laracasts.com/series/lets-build-a-forum-with-laravel).

Forum was made with Laravel 5.8, Vue.js and Bootstrap 4.

## Requirements
You need to have docker on your computer.

## Installation
Clone repository
```
git clone https://github.com/tomopongrac/forum.git
```
Open directory
```
cd forum
```

Start the docker
```
docker-compose up -d --build
```

Find out your container id with command
```
docker ps
```

Connect to container bash
```
docker exec -it container_id bash
```

In your container make migration
```
php artisan make:migraton
```

And populate database with sample data
```
php artisan db:seed
```

## Features
Only authenticated users may participate in forum.

For every users there is activity feed on their profile.

Administrator can lock a threads.

Creator of thread may choose reply as best answer.

Users may favorite replies.

User get notification if some mentions them in reply.

Spam detection.

Trending threads from sidebar are recording to redis.
