<?php

namespace App\Traits;

trait FCMTrait{
  protected $endpoint = 'https://fcm.googleapis.com/fcm/send';
  protected $recipients;
  protected $topic;
  protected $data;
  protected $notification;
  protected $timeToLive;
  protected $priority;
  protected $package;
  protected $serverKey;

  
  public function to($recipients) {
    $this->serverKey = env('FCM_SERVER_KEY', '');
    $this->recipients = $recipients;
    return $this;
  }

  public function toTopic($topic) {
    $this->topic = $topic;
    return $this;
  }


  public function data($data = []) {
    $this->data = $data;
    return $this;
  }


  public function notification($notification = []) {
    $this->notification = $notification;
    return $this;
  }


  public function priority(string $priority) {
    $this->priority = $priority;
    return $this;
  }


  public function timeToLive($timeToLive) {
      if($timeToLive < 0) $timeToLive = 0; // (0 seconds)
      if($timeToLive > 2419200) $timeToLive = 2419200; // (28 days)
      $this->timeToLive = $timeToLive;

      return $this;
  }
  

  public function setPackage($package) {
    $this->package = $package;
    return $this;
  }

  
  public function send() {
    $headers = [
      'Authorization: key=' . $this->serverKey,
      'Content-Type: application/json',
    ];

    $payloads = [
      'content_available' => true,
      'priority'          => isset($this->priority) ? $this->priority : 'high',
      'data'              => $this->data,
      'notification'      => $this->notification
    ];
    
    if(!empty($this->package))
      $payloads['restricted_package_name'] = $this->package;

    if($this->timeToLive !== null && $this->timeToLive >= 0)
      $payloads['time_to_live'] = (int) $this->timeToLive;

    if($this->topic)
      $payloads['to'] = "/topics/{$this->topic}";
    else
      $payloads['registration_ids'] = $this->recipients;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payloads));
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return $result;
  }
}