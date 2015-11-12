<?php
/**
 * Simple class to load settings
 */
class Pay_Config{
   protected $config = array();
   
   public function __construct(){
       $dir = realpath(dirname(__FILE__));

       $config=array();
        //You can find your API token here: https://admin.pay.nl/my_merchant (on the bottom)
       $config['apitoken'] = 'e2eb9b8a03e0a06edbb1f7c4a5aaa370a3048308';
        //You can find your service id here: https://admin.pay.nl/programs/programs (The serviceId starts with SL- )
       $config['serviceId'] = 'SL-9174-1550';
       $this->config = $config;
   } 

   public function __get($name) {
       return (string) $this->config[$name];
   }
}