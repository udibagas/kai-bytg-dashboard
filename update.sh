#!/bin/bash

git pull
php artisan migrate --force
cd frontend && npm run build
