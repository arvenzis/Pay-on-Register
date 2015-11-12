<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'pay_on_register/config.php';

/**
 * Class Pay_on_register
 */
class Pay_on_register {

    /**
     * @var string
     */
    var $return_data	= '';
    var $p_limit = '';

    /**
     * Use library to get variables and functions that need to be used multiple times in this file
     */
    public function __construct(){
        ee()->load->library('pay_on_register_lib');
    }

    /**
     * Get payment status and set member group status to member
     */
    function payment_check()
    {
        //Autoloader inladen zodat we niet handmatig de classes hoeven te includen
        require_once('libraries/pay/Autoload.php');

        //Checken of de betaling gelukt is adhv het transactie id

        //Get API data (token, serviceId) from library
        $apiData = ee()->pay_on_register_lib->set_api_data();

        # Setup API Url
        /** @var  $strUrl */
        $strUrl = $apiData[4];

        # Add arguments
        /** @var  $arrArguments */
        $arrArguments = array();
        $arrArguments['token'] = $apiData[0];
        $arrArguments['transactionId'] = $_GET['orderId'];

        # Prepare complete API URL
        $strUrl = $strUrl.http_build_query($arrArguments);
        $arrResult = unserialize(@file_get_contents($strUrl));

        //if state outputs 100, the payment has been succesful
        if($arrResult['paymentDetails']['state'] == 100){
            //Payment has been succesful. Set 'member' status to member
            ee()->db->where('member_id', $_GET['member_id'])->update('members', array('group_id' => 5));

            # Cleanup API data
            unset($strUrl, $arrArguments);

            ee()->functions->redirect(ee()->pay_on_register_lib->set_site_url().'/account/registreren/success');
        }else{
            //The payment failed. Remove user and show error message.

            # Cleanup API data
            unset($strUrl, $arrArguments);

            ee()->db->where('member_id', $_GET['member_id'])->delete('members');
            ee()->functions->redirect(ee()->pay_on_register_lib->set_site_url().'/account/registreren/failed');
        }

    }

    /**
     * Check closing date and set member group to
     * "Niet betaald" of closing date is in the past
     * @return bool
     */
    public function checkClosingDate()
    {
        /** @var  $settings */
        $settings = ee()->db->select('settings')
            ->where('class', 'Pay_on_register_ext')
            ->get('extensions')
            ->row('settings');

        $settings = unserialize($settings);
        $time = strtotime($settings['sluitingsdatum']);

        if(date('U',$time) > date('U')){
            return true;
        } else {
            ee()->db->where('group_id', 5)
                ->update('members', array('group_id' => 7));
        }

    }

    /**
     * Pay directly from a link in the template
     */
    function pay_directly_from_link(){

        /** @var  $settings
         *  Get extension settings from library
         */
        $settings = ee()->pay_on_register_lib->get_extension_settings();

        $member_id = ee()->session->userdata('member_id');

        //Get entry id from exp_channel_titles with the member id
        $entry_id = ee()->channel_data->get_channel_entries(13, array(
            'where'     => array('channel_titles.author_id' => $member_id),
            'order_by'  => 'title',
            'sort'      => 'ASC',
            'limit'     => 1
        ));

        //Get children (member_kinderen) from exp_grid_field_66 with the entry id
        $result = ee()->db->select('*')
            ->where('entry_id', $entry_id->row('entry_id'))
            ->get('exp_channel_grid_field_66')
            ->result();

        //Multiply the children with the price (one child is €5, two children is €10)
        $prijsPerKind = $settings['prijs_per_kind'];
        $kinderen = count($result);

        $member_id = $member_id;

        $multiply = $kinderen * $prijsPerKind;

        $totalPrice = $multiply.'00';

        //Get API data (token, serviceId) from library
        $apiData = ee()->pay_on_register_lib->set_api_data();

        # Setup API URL
        $strUrl = $apiData[3];

        # Add arguments
        $arrArguments['token'] = $apiData[0];
        $arrArguments['serviceId'] = $apiData[1];
        $arrArguments['amount'] = $totalPrice;
        $arrArguments['finishUrl'] = ee()->pay_on_register_lib->set_site_url().'?ACT='.ee()->pay_on_register_lib->fetch_action_id('Pay_on_register', 'payment_check_from_link').'&member_id='.$member_id;
        $arrArguments['transaction']['description'] = $apiData[2];
        $arrArguments['ipAddress'] = $_SERVER['REMOTE_ADDR'];

        # Prepare and call API URL
        $strUrl .= http_build_query($arrArguments);
        $jsonResult = file_get_contents($strUrl);
        $result = json_decode($jsonResult);

        ee()->functions->redirect($result->transaction->paymentURL);
    }

    /**
     * Get payment status and set member group status to member from link
     */
    function payment_check_from_link()
    {
        //Autoloader inladen zodat we niet handmatig de classes hoeven te includen
        require_once('libraries/pay/Autoload.php');

        //Checken of de betaling gelukt is adhv het transactie id
        //Get API data (token, serviceId) from library
        $apiData = ee()->pay_on_register_lib->set_api_data();

        # Setup API Url
        /** @var  $strUrl */
        $strUrl = $apiData[4];

        # Add arguments
        /** @var  $arrArguments */
        $arrArguments = array();
        $arrArguments['token'] = $apiData[0];
        $arrArguments['transactionId'] = $_GET['orderId'];

        # Prepare complete API URL
        $strUrl = $strUrl.http_build_query($arrArguments);
        $arrResult = unserialize(@file_get_contents($strUrl));

        //if state outputs 100, the payment has been succesful
        if($arrResult['paymentDetails']['state'] == 100){
            //Payment has been succesful. Set 'member' status to member
            ee()->db->where('member_id', $_GET['member_id'])->update('members', array('group_id' => 5));

            # Cleanup API data
            unset($strUrl, $arrArguments);

            ee()->functions->redirect(ee()->pay_on_register_lib->set_site_url().'/sporten/success');
        }else{
            //The payment failed. Remove user and show error message.

            # Cleanup API data
            unset($strUrl, $arrArguments);

            ee()->functions->redirect(ee()->pay_on_register_lib->set_site_url().'/sporten/failed');
        }

    }

    /**
     * Get the payment link with the action id from pay_on_register_lib.php
     * @return string
     */
    function payment_link(){
        return ee()->pay_on_register_lib->set_site_url().'?ACT='.ee()->pay_on_register_lib->fetch_action_id('Pay_on_register', 'pay_directly_from_link');
    }


}

/* End of file mod.download.php */
/* Location: ./system/expressionengine/third_party/download/mod.download.php */