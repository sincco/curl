<?php
# NOTICE OF LICENSE
#
# This source file is subject to the Open Software License (OSL 3.0)
# that is available through the world-wide-web at this URL:
# http://opensource.org/licenses/osl-3.0.php
#
# -----------------------
# @author: Iván Miranda
# @version: 1.0.0
# -----------------------
# Simple class for CURL implementation and DOM crawler
# -----------------------

namespace Sincco\Tools;

include(dirname(__FILE__) . '/simple_html_dom.php');

/**
 * Simple class for CURL implementation and DOM crawler
 */
class Curl
{
    protected $handler;
    protected $options;

    public function __construct()
    {
        $this->options = [];
        $this->handler = null;
        $this->addOption(CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        $this->addOption(CURLOPT_HEADER, true);
        $this->addOption(CURLOPT_RETURNTRANSFER, true);
        $this->addOption(CURLOPT_SSL_VERIFYPEER, true);
        $this->addOption(CURLOPT_FOLLOWLOCATION, 1);
        // $this->addOption(CURLOPT_CAINFO, "/var/www/cacert.pem");
    }

    /**
     * Init CURL object
     * @return none
     */
    private function _init()
    {
        $this->handler = curl_init();
        $this->_setOptions();
    }

    /**
     * Set options asigned by user
     */
    private function _setOptions() {
        foreach ($this->options as $option => $value) {
            curl_setopt($this->handler, $option, $value);
        }
    }

    /**
     * @param option CURL CONSTANT
     * @param value  mixed
     */
    public function addOption($option, $value) {
        $this->options[$option] = $value;
    }    

    /**
     * @param  url string
     * @return string html content
     */
    public function get($url)
    {
        $this->_init();
        curl_setopt($this->handler, CURLOPT_URL, $url);
        $data = curl_exec($this->handler);
        curl_close($this->handler);
        return $data;
    }

    /**
     * @param  url string
     * @return simple_html_dom object
     */
    public function getDom($url)
    {
        $html = $this->get($url);
        return str_get_html($html);

    }

    /**
     * Asign a basic authentication configuration
     * @param username string
     * @param password string
     */
    public function setBasicAuthentication($username, $password = '')
    {
        $this->addOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $this->addOption(CURLOPT_USERPWD, $username . ':' . $password);
    }

    /**
     * Asign a digest authentication
     * @param username string
     * @param password string
     */
    public function setDigestAuthentication($username, $password = '')
    {
        $this->addOption(CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        $this->addOption(CURLOPT_USERPWD, $username . ':' . $password);
    }

    /**
     * @param params array
     */
    public function setPost($params)
    {
        $fields_string = '';
        foreach ($params as $key=>$value) { 
            $fields_string .= $key.'='.$value.'&';
            $params[$key] = urlencode($value);
        }
        rtrim($fields_string, '&');
        $this->addOption(CURLOPT_POST, count($params));
        $this->addOption(CURLOPT_POSTFIELDS, $fields_string);
    }
}