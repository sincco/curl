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
    protected $header;

    public function __construct()
    {
        $this->options = [];
        $this->header = [];
        $this->handler = null;
        $this->addOption(CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        $this->addOption(CURLOPT_HEADER, false);
        $this->addOption(CURLOPT_RETURNTRANSFER, true);
        $this->addOption(CURLOPT_SSL_VERIFYPEER, true);
        $this->addOption(CURLOPT_FOLLOWLOCATION, 1);
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
        curl_setopt($this->handler, CURLOPT_HTTPHEADER, $this->header);
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
    public function get($url, $params = [])
    {
        $this->_init();
        $this->addOption(CURLOPT_POST, count($params));
        $this->addOption(CURLOPT_POSTFIELDS, $params);
        curl_setopt($this->handler, CURLOPT_URL, $url);
        $data = curl_exec($this->handler);
        curl_close($this->handler);
        return $data;
    }

    /**
     * @param  url string
     * @return simple_html_dom object
     */
    public function getDom($url, $params = [])
    {
        $html = $this->get($url, $params);
        return str_get_html($html);

    }

    /**
     * @param  url string
     * @return json object
     */
    public function getJson($url, $params = [])
    {
        $this->header[] = 'Content-Type: application/json';
        if (count($params) > 0) {
            $params = json_encode($params);
            $this->header[] = 'Content-Length: ' . strlen($params);
            $this->addOption(CURLOPT_POSTFIELDS, $params);
        }
        $this->_init();
        curl_setopt($this->handler, CURLOPT_URL, $url);
        $data = curl_exec($this->handler);
        curl_close($this->handler);
        return json_decode($data);
    }

    /**
     * @param string Set custom authorization
     */
    public function setAuthorization($data)
    {
        $this->header[] = 'Authorization: '.$data;
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
     * @param method string Metod for consumption
     */
    public function setMethod($method)
    {
        $this->addOption(CURLOPT_CUSTOMREQUEST, $method);
    }
}