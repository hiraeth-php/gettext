<?php

namespace Hiraeth\Gettext;

use Hiraeth;

/**
 *
 */
class ApplicationProvider implements Hiraeth\Provider
{
	/**
	 * {@inheritDoc}
	 */
	static public function getInterfaces(): array
	{
		return [
			Hiraeth\Application::class
		];
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(object $instance, Hiraeth\Application $app): object
	{
		foreach ($app->getConfig('*', 'gettext.domains.locales', []) as $domains) {
			foreach ($domains as $domain => $path) {
				bindtextdomain($domain, $app->getDirectory($path)->getPathname());
				bind_textdomain_codeset($domain, 'UTF-8');
			}
		}

		textdomain($app->getConfig('packages/gettext', 'gettext.domains.default', 'app'));
		setlocale(LC_ALL, ...explode(',', $app->getEnvironment('LOCALE', 'en_US.utf8')));

		return $instance;
	}
}
