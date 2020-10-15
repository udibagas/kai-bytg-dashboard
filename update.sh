#!/bin/bash

git pull
npm run build
cd backend && php artisan migrate --force
