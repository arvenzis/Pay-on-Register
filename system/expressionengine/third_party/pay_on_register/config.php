<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! defined('PAY_ON_REGISTER_NAME'))
{
	define('PAY_ON_REGISTER_NAME', 'Pay on Register');
	define('PAY_ON_REGISTER_CLASS', 'Pay_on_register');
	define('PAY_ON_REGISTER_MAP', 'pay_on_register');
	define('PAY_ON_REGISTER_VERSION', '1.0');
	define('PAY_ON_REGISTER_DESCRIPTION', 'Sport van de Maand betalen voor registreren');
	define('PAY_ON_REGISTER_DOCS', '');
	define('PAY_ON_REGISTER_DEVOTEE', '');
	define('PAY_ON_REGISTER_AUTHOR', 'Karen Bosch');
	define('PAY_ON_REGISTER_DEBUG', true);
	define('PAY_ON_REGISTER_STATS_URL', '');
}

//configs
$config['name'] = PAY_ON_REGISTER_NAME;
$config['version'] = PAY_ON_REGISTER_VERSION;

//load compat file
require_once(PATH_THIRD.PAY_ON_REGISTER_MAP.'/compat.php');

/* End of file config.php */
/* Location: /system/expressionengine/third_party/default/config.php */