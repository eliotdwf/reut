#!/bin/sh
if curl -s --fail http://localhost:8006 > /dev/null; then
    exit 0
else
    exit 1
fi
