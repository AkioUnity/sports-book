<?php

class NordicBet extends FeedsAppModel
{
    public $name = 'NordicBet';

    /**
     * Downloads and returns xml
     *
     * @param $name
     * @param $type
     * @param $url
     * @return DOMDocument|SimpleXMLElement
     */
    public function download($name, $type, $url)
    {
        $directory = APP . 'tmp' . DS . 'xml' . DS . $name . DS;

        if(file_exists($directory . Inflector::slug($type) . '.xml')) {
            return Xml::build($directory . Inflector::slug($type) . '.xml');
        }

        if(!file_exists($directory))
            mkdir( $directory, 0777, true );

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $data = curl_exec($ch);

        curl_close($ch);

        file_put_contents($directory . Inflector::slug($type) . '.xml', $data);

        return Xml::build($data);
    }
}