<?php

function getenvOrDefault($label, $default) {
	return getenv($label) !== false ? getenv($label) : $default;
}

return [
    'authorisation.api.base.url' => getenv('AUTHORISATION_API_BASE_URL'), # TODO is this a duplicate of kong.external.url ?
    'service.api.base.url' => getenv('SERVICE_API_BASE_URL'),
    'hub.apikey' => getenv('COMP_AUTH_KEY'),
	'hub.version' => getenvOrDefault('HUB_VERSION', 'latest'),
	'kong.internal.url' => getenv('KONG_INTERNAL_URL'),
	'kong.external.url' => getenv('KONG_EXTERNAL_URL'),
	'kong.provision.key' => getenv('KONG_PROVISION_KEY'),

    // 'email.from.name' => getenv('EMAIL_FROM_NAME'),
	'email.from' => getenv('EMAIL_FROM'),
    'email.password' => getenv('EMAIL_PASSWORD'),
    'email.host' => getenv('EMAIL_HOST'),
    'email.port' => getenv('EMAIL_PORT'),

	'incentive.server.base.url' => getenv('INCENTIVE_SERVER_BASE_URL'),
	'task.manager.base.url' => getenv('TASK_MANAGER_BASE_URL'),
	'profile.manager.base.url' => getenv('PROFILE_MANAGER_BASE_URL'),
	'logging.component.base.url' => getenv('LOGGING_COMPONENT_BASE_URL'),
	'profile.cache.time.secs' => getenvOrDefault('PROFILE_CACHE_TIME_SECS', 60),

    'google.reCaptcha.secret' => getenv('GOOGLE_RACAPTCHA_SECRET'),
    'google.reCaptcha.site' => getenv('GOOGLE_RACAPTCHA_SITE'),

    'env' => 'staging',
];
