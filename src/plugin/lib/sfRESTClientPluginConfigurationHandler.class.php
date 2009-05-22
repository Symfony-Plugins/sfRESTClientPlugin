<?php 
/*
 * This file is part of the sfRESTClientPlugin package.
 * 
 * (c) 2008 John Lianoglou <prometheas@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Configuration handler for plugin's rest_services.yml config file.
 * 
 * @package     sfRESTClientPlugin
 * @subpackage  config
 * @author      John Lianoglou <prometheas@gmail.com>
 * 
 * @see         http://en.wikipedia.org/wiki/Representational_State_Transfer
 */
class sfRESTClientPluginConfigurationHandler extends sfYamlConfigHandler
{
	/**
	 * Executes this configuration handler.
	 *
	 * @param array An array of absolute filesystem path to a configuration file.
	 *
	 * @return string Data to be written to a cache file.
	 *
	 * @throws sfConfigurationException  If a requested configuration file does not exist or is not readable.
	 * @throws sfParseException          If a requested configuration file is improperly formatted.
	 */
	public function execute($configFiles)
	{
		// parse the yaml
		$raw_config_array = $this->parseYamls($configFiles);

		// Parse configuration
		$compiled_configuration = array();

		// -- Data sources
		foreach ($raw_config_array['services'] as $svc_name => $svc_info)
		{
			// Only process enabled datasources
			if (isset($svc_info['enabled']) && $svc_info['enabled'] === true)
			{
				$compiled_configuration[$svc_name] = $svc_info;
			}
		}

		// compile data
		$retval = sprintf("<?php\n".
			"// auto-generated by %s\n".
			"// date: %s\nsfConfig::set('sfRESTClientPlugin_services', \n%s\n);\n?>",
			__CLASS__, date('Y/m/d H:i:s'), var_export($compiled_configuration, true));

		return $retval;
	}
}