<?php

// grab recaptcha library
require_once "recaptchalib.php";

class VerifyRecaptcha
{
    const key = "6LfBLjAUAAAAAMeknAqp_c5WMe_rhlqtpupkauxO";

    public static function verify($recaptchaResponse = "")
    {
        $reCaptcha = new ReCaptcha(self::key);

        return $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $recaptchaResponse
        );
    }
}