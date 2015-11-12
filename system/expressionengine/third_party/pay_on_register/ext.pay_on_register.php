<?php
/**
 * Created by PhpStorm.
 * User: kbo01
 * Date: 3-11-2015
 * Time: 15:08
 */
class Pay_on_register_ext {

    /**
     * @var string
     */
    var $name           = 'Pay on Register';
    var $version        = '1.0';
    var $description    = 'Payment through pay.nl after registering';
    var $settings_exist = 'y';
    var $docs_url       = ''; // 'https://ellislab.com/expressionengine/user-guide/';
    var $settings       = array();

    /**
     * Constructor
     *
     * @param   mixed   Settings array or empty string if none exist.
     */
    function __construct($settings='')
    {
        $this->settings = $settings;
        ee()->load->library('pay_on_register_lib');
    }

    /**
     * Activate Extension
     *
     * This function enters the extension into the exp_extensions table
     *
     * @see https://ellislab.com/codeigniter/user-guide/database/index.html for
     * more information on the db class.
     *
     * @return void
     */
    function activate_extension()
    {
        $this->settings = array(
            'prijs_per_kind'    => 5,
            'sluitingsdatum'    => '05-11-2016',
        );

        $hooks = array(
            "zoo_visitor_register_end"  =>  "redirectToPayment"
        );

        foreach ($hooks as $hook => $method) {
            $data = array(
                'class' => __CLASS__,
                'method' => $method,
                'hook' => $hook,
                'settings' => serialize($this->settings),
                'priority' => 10,
                'version' => $this->version,
                'enabled' => 'y'
            );
        }
        ee()->db->insert('extensions', $data);
    }

    /**
     * Update Extension
     *
     * This function performs any necessary db updates when the extension
     * page is visited
     *
     * @return  mixed   void on update / false if none
     */
    function update_extension($current = '')
    {
        if ($current == '' OR $current == $this->version)
        {
            return FALSE;
        }

        if ($current < '1.0')
        {
            // Update to version 1.0
        }

        ee()->db->where('class', __CLASS__);
        ee()->db->update(
            'extensions',
            array('version' => $this->version)
        );
    }

    /**
     * Disable Extension
     *
     * This method removes information from the exp_extensions table
     *
     * @return void
     */
    function disable_extension()
    {
        ee()->db->where('class', __CLASS__);
        ee()->db->delete('extensions');
    }

    /**
     * @return array
     */
    function settings()
    {
        $settings = array();

        // Creates a text input with a default value of 5 euros
        $settings['prijs_per_kind'] = array('i', '', "5");
        $settings['sluitingsdatum'] = array('i', '', "5-11-2016");

        return $settings;
    }

    /**
     * @param $member_data
     */
    function redirectToPayment($member_data)
    {
        ee()->load->library('pay_on_register_lib');

        //Multiply the children with the price (one child is €5, two children is €10)
        $prijsPerKind = $this->settings['prijs_per_kind'];
        $kinderen = count($member_data['member_kinderen']);
        $member_id = $member_data['member_id'];

        $multiply = $kinderen * $prijsPerKind;

        $totalPrice = $multiply.'00';

        //Pay.nl API gebruiken
        //Get API data (token, serviceId) from library
        $apiData = ee()->pay_on_register_lib->set_api_data();

        # Setup API URL
        $strUrl = $apiData[3];

        # Add arguments
        $arrArguments['token'] = $apiData[0];
        $arrArguments['serviceId'] = $apiData[1];
        $arrArguments['amount'] = $totalPrice;
        $arrArguments['finishUrl'] = ee()->pay_on_register_lib->set_site_url().'?ACT='.ee()->pay_on_register_lib->fetch_action_id('Pay_on_register', 'payment_check').'&member_id='.$member_id;
        $arrArguments['transaction']['description'] = $apiData[2];
        $arrArguments['ipAddress'] = $_SERVER['REMOTE_ADDR'];

        # Prepare and call API URL
        $strUrl .= http_build_query($arrArguments);
        $jsonResult = file_get_contents($strUrl);
        $result = json_decode($jsonResult);

        ee()->functions->redirect($result->transaction->paymentURL);
    }
}