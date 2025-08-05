#!/bin/bash

if ! grep -q "registry.gitlab.utc.fr" ~/.docker/config.json; then
    echo "You need to login to the registry first see 'docker login registry.gitlab.utc.fr'"
    exit 1
fi

docker build -f .docker/Dockerfile -t registry.gitlab.utc.fr/simde/reut:latest --target app .

docker push registry.gitlab.utc.fr/simde/reut:latest
