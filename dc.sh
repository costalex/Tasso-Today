#!/usr/bin/env bash

case $1 in
    hot)
        echo "docker-compose exec front npm run hot"
        docker-compose exec front npm run hot
        ;;
    npm)
        echo "docker-compose run --rm front npm ${@:2}"
        docker-compose run --rm front npm "${@:2}"
        ;;
    sentry)
        echo "docker-compose run --rm front node_modules/.bin/sentry-cli releases files $2 upload-sourcemaps --url-prefix https://www.tasso.today/js public/js"
        docker-compose run --rm front node_modules/.bin/sentry-cli releases files ${2} upload-sourcemaps --url-prefix https://www.tasso.today/js public/js
        ;;
    composer)
        echo "docker-compose run --rm web composer ${@:2}"
        docker-compose run --rm --user www-data web composer "${@:2}"
        ;;
    artisan)
        echo "docker-compose run --rm web php artisan ${@:2}"
        docker-compose run --rm --user www-data web php artisan "${@:2}"
        ;;
    dep)
        echo "docker-compose run --rm web vendor/bin/dep ${@:2}"
        docker-compose run --rm web vendor/bin/dep "${@:2}"
        ;;
    *)
        echo "docker-compose $@"
        docker-compose "$@"
        ;;
esac
