#!/bin/bash

cd /app

# Selecting starting mode (dev/prod)

PROD=0
if [ "${PROD_MODE}" == "1" ]; then
	PROD=1
	echo "Starting in PROD mode"
else
	echo "Starting in DEV mode"
fi

echo -e "$PROD\nyes\nAll" | php init

echo -e "yes" | php yii migrate/up

exec supervisord -n
