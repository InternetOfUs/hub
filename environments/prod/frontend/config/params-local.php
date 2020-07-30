<?php

function getenvOrDefault($label, $default) {
	return getenv($label) !== false ? getenv($label) : $default;
}

return [
    'service.api.base.url' => getenv('SERVICE_API_BASE_URL'),
	'service.api.api.key' => getenvOrDefault('SERVICE_API_APIKEY', null),
	'hub.version' => getenvOrDefault('HUB_VERSION', 'latest'),
	'kong.url' => getenv('KONG_URL'),
	'kong.provision.key' => getenv('KONG_PROVISION_KEY'),
];
