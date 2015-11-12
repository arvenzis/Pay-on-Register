<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//updates
$this->updates = array(
	//'1.2',
);

//Default Post
$this->default_post = array(
	'license_key'   		=> '',
	'report_date' 			=> time(),
	'report_stats' 			=> true,
	'stock_grid_field' 			=> '',
	'copy_confirmation' 			=> '',
);

//overrides
$this->overide_settings = array(
	//'gmaps_icon_dir' => '[theme_dir]images/icons/',
	//'gmaps_icon_url' => '[theme_url]images/icons/',
);

// Backwards-compatibility with pre-2.6 Localize class
$this->format_date_fn = (version_compare(APP_VER, '2.6', '>=')) ? 'format_date' : 'decode_date';

//mcp veld header
$this->table_headers = array(
	PAY_ON_REGISTER_MAP.'_submission_id' => array('data' => lang(PAY_ON_REGISTER_MAP.'_submission_id'), 'style' => 'width:10%;'),
	PAY_ON_REGISTER_MAP.'_created' => array('data' => lang(PAY_ON_REGISTER_MAP.'_created'), 'style' => 'width:50%;'),
	PAY_ON_REGISTER_MAP.'_member' => array('data' => lang(PAY_ON_REGISTER_MAP.'_member'), 'style' => 'width:30%;'),
	'actions' => array('data' => '', 'style' => 'width:10%;')
);

$this->fieldtype_settings = array(
	array(
		'label' => lang('license'),
		'name' => 'license',
		'type' => 't', // s=select, m=multiselect t=text
		//'options' => array('No', 'Yes'),
		'def_value' => '',
		'global' => true, //show on the global settings page
	),

);

/* End of file settings.php  */
/* Location: ./system/expressionengine/third_party/default/settings.php */