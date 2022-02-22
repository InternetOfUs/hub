#!/bin/bash

cp dev-env/.htaccess frontend/web/.

docker-compose -f docker-compose-dev.yml up -d 
docker-compose -f docker-compose-dev.yml exec frontend bash
docker-compose -f docker-compose-dev.yml down