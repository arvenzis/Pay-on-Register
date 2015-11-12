<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Pay_on_register_upd {

    var $version = '1.0';

    /**
     * Module Installer
     *
     * @access	public
     * @return	bool
     */
    function install()
    {
        ee()->load->dbforge();

        //Install modules
        $data = array(
            'module_name' => 'Pay_on_register' ,
            'module_version' => $this->version,
            'has_cp_backend' => 'n',
            'has_publish_fields' => 'n'
        );
        ee()->db->insert('modules', $data);

        //Install actions
        $data = array(
            'class'		=> 'Pay_on_register',
            'method'	=> 'payment_check',
        );

        ee()->db->insert('actions', $data);

        $data = array(
            'class'     => 'Pay_on_register',
            'method'    => 'pay_directly_from_link'
        );

        ee()->db->insert('actions', $data);

        $data = array(
            'class'     => 'Pay_on_register',
            'method'    => 'payment_check_from_link'
        );

        ee()->db->insert('actions', $data);

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Module Uninstaller
     *
     * @access	public
     * @return	bool
     */
    function uninstall()
    {
        ee()->load->dbforge();

        ee()->db->where('module_name', 'Pay_on_register');
        ee()->db->delete('modules');

        ee()->db->where('class', 'Pay_on_register');
        ee()->db->delete('actions');

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Module Updater
     *
     * @access	public
     * @return	bool
     */

    function update($current='')
    {
        return TRUE;
    }

}
/* END Class */

/* End of file upd.download.php */
/* Location: ./system/expressionengine/third_party/modules/download/upd.download.php */