<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'pay_on_register/config.php';

/**
 * Class Pay_on_register_lib
 */
class Pay_on_register_lib {

    public function __construct(){
        ee()->load->driver('channel_data');
    }

    // ----------------------------------------------------------------------

    /**
     * 	Fetch Action IDs
     *
     * 	@access public
     *	@param string
     * 	@param string
     *	@return mixed
     */
    public function fetch_action_id($class = '', $method)
    {
        ee()->db->select('action_id');
        ee()->db->where('class', $class);
        ee()->db->where('method', $method);
        $query = ee()->db->get('actions');

        if ($query->num_rows() == 0)
        {
            return FALSE;
        }

        return $query->row('action_id');
    }

    /**
     * @return mixed
     */
    public function get_extension_settings(){
        /** @var  $settings */
        $settings = ee()->db->select('settings')
            ->where('class', 'Pay_on_register_ext')
            ->get('extensions')
            ->row('settings');

        $settings = unserialize($settings);

        return $settings;
    }

    /**
     * @return array
     */
    public function set_api_data(){

        /**
         * $apiData[0]=token
         * $apiData[1]=serviceId
         * $apiData[2]=description
         * $apiData[3]=API URL
         * $apiData[4]=API seriablize URL
        */
        $apiData = array('e2eb9b8a03e0a06edbb1f7c4a5aaa370a3048308','SL-9174-1550','Sport van de maand','https://rest-api.pay.nl/v5/Transaction/start/json?','https://rest-api.pay.nl/v3/Transaction/info/array_serialize?');

        return $apiData;
    }

    /**
     * Set site url to have to define it only once
     * @return string
     */
    public function set_site_url(){
        $site_url = 'http://sportvandemaand.local';

        return $site_url;
    }
}