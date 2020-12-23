<?php

function getenvOrDefault($label, $default) {
	return getenv($label) !== false ? getenv($label) : $default;
}

return [
    'authorisation.api.base.url' => getenv('AUTHORISATION_API_BASE_URL'),
    'service.api.base.url' => getenv('SERVICE_API_BASE_URL'),
    'service.api.api.key' => getenvOrDefault('SERVICE_API_APIKEY', null),
	'hub.version' => getenvOrDefault('HUB_VERSION', 'latest'),
	'kong.internal.url' => getenv('KONG_INTERNAL_URL'),
	'kong.external.url' => getenv('KONG_EXTERNAL_URL'),
	'kong.provision.key' => getenv('KONG_PROVISION_KEY'),

	'elasticmail.api.key' => getenv('ELASTICMAIL_API_KEY'),
    'email.from.name' => getenv('EMAIL_FROM_NAME'),
	'email.from' => getenv('EMAIL_FROM'),
];
