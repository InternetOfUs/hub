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
if [ $? -ne 0 ]; then
    echo "Error! Something went wrong while initialising project.."
	exit 1
fi

echo -e "yes" | php yii migrate/up
if [ $? -ne 0 ]; then
    echo "Error! Something went wrong while applying the migrations.."
	exit 1
fi

exec supervisord -n
