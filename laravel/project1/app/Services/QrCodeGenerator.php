<?php

namespace App\Services;

use Intervention\Image\Facades\Image;

class QrCodeGenerator
{
    // 9cm -> 340px
    // 10cm -> 378px
    // 11cm -> 416px

    //URL OF GOOGLE CHART API
    private $apiUrl = 'http://chart.apis.google.com/chart';
    // DATA TO CREATE QR CODE
    private $data;


    // Function which is used to generate the URL type of QR Code.
    public function url($url = null)
    {
        $this->data = preg_match("#^https?\:\/\/#", $url)
            ? $url
            : "http://{$url}";
    }

    // Function which is used to generate the TEXT type of QR Code.
    public function text($text)
    {
        $this->data = $text;
    }

    // Function which is used to generate the EMAIL type of QR Code.
    public function email($email = null, $subject = null, $message = null)
    {
        $this->data = "MATMSG:TO:{$email};SUB:{$subject};BODY:{$message};;";
    }

    // Function which is used to generate the PHONE type of QR Code.
    public function phone($phone)
    {
        $this->data = "TEL:{$phone}";
    }

    // Function which is used to generate the SMS type of QR Code.
    public function sms($phone = null, $msg = null)
    {
        $this->data = "SMSTO:{$phone}:{$msg}";
    }

    // Function which is used to generate the CONTACT type of QR Code.
    public function contact($name = null, $address = null, $phone = null, $email = null)
    {
        $this->data = "MECARD:N:{$name};ADR:{$address};TEL:{$phone};EMAIL:{$email};;";
    }

    //Function which is used to save the qrcode image file.
    public function qrcode($size = 400, $filename = null)
    {
        Image::make(
            $this->apiUrl
            . "?chs={$size}x{$size}&cht=qr&chld=H|6&chl=" . urlencode($this->data)
        )
            ->flip('h')
            ->save($filename);
    }
}
