<?php

final class RunCpa
{
    protected $offerHash = false;
    protected $offerPublicHash = 'f48e65462740a2e68edfec393acc8758';
    protected $partnerToken = '0j3Q-j5ks8w1Jx9ZWkPbkXCpEIwsHMo0';
    protected static $instance;
    protected static $baseUrl = 'https://runcpa.com';
    protected $webMasterId = false;

    public static function getInstance()
    {
        if (!self::$instance)
            self::$instance = new self;
        return self::$instance;
    }

    public function captureRequest()
    {
        if (isset($_GET['track_id']))
            setcookie('run_cpa_track_id', $_GET['track_id'], time() + 60 * 60 * 24 * 30);
        if (isset($_GET['webmaster_id']))
            $this->webMasterId = $_GET['webmaster_id'];
    }

    public function getTrackId()
    {
        if (!isset($_COOKIE['run_cpa_track_id']))
            return false;
        return $_COOKIE['run_cpa_track_id'];
    }

    public function cpa()
    {
        return $this->track('cpa');
    }

    public function cpl()
    {
        return $this->track('cpl');
    }

    public function cps()
    {
        return $this->track('cps');
    }

    public function generateConversion($conversionName)
    {
        return $this->track($conversionName);
    }

    public function generateRevenueShare($conversionName, $sum)
    {
        if (!isset($_COOKIE['run_cpa_track_id']))
            return false;
        if ($this->offerHash)
            $url = self::$baseUrl . '/callbacks/events/revenue/' . $this->offerHash . '/' .
                $conversionName . '/' . $_COOKIE['run_cpa_track_id'] . '/' . $sum;
        elseif ($this->partnerToken)
            $url = self::$baseUrl . '/callbacks/events/revenue-partner/' . $this->partnerToken . '/' .
                $conversionName . '/' . $_COOKIE['run_cpa_track_id'] . '/' . $sum;
        else
            return false;
        $c = @file_get_contents($url);
        $r = json_decode($c, true);
        return isset($r['status']) && $r['status'] == 'ok';
    }

    public function trackForWebMaster()
    {
        if ($this->webMasterId == 'test') {
            echo "Test is ok";
            return;
        }
        if ($this->webMasterId)
            header('Location: ' . self::$baseUrl . '/getoffer/' . $this->webMasterId . '-' .
                $this->offerPublicHash);
        else
            echo "Parameter webmaster_id was not given";
    }

    protected function track($type)
    {
        if (!isset($_COOKIE['run_cpa_track_id']))
            return false;
        if ($this->offerHash)
            $url = self::$baseUrl . '/callbacks/event/s2s/' . $this->offerHash . '/' . $type . '/' .
                $_COOKIE['run_cpa_track_id'];
        elseif ($this->partnerToken)
            $url = self::$baseUrl . '/callbacks/event/s2s-partner/' . $this->partnerToken . '/' . $type . '/' .
                $_COOKIE['run_cpa_track_id'];
        else
            return false;

        $c = @file_get_contents($url);
        $r = json_decode($c, true);
        
        return isset($r['status']) && $r['status'] == 'ok';
    }
}