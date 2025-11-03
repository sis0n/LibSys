<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class MailService
{

  private $mailer;

  public function __construct()
  {
    $this->mailer = new PHPMailer(true);
    $this->setup();
  }

  private function setup()
  {
    try {
      $this->mailer->isSMTP();
      $this->mailer->Host       = 'smtp.gmail.com';  
      $this->mailer->SMTPAuth   = true;
      $this->mailer->Username   = 'figmaUsers01@gmail.com';
      $this->mailer->Password   = 'hxfxtekesuolodaq';
      $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $this->mailer->Port       = 587;

      $this->mailer->SMTPOptions = array(
        'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      );

      $this->mailer->setFrom('no-reply@libsys.com', 'LibSys Support');
    } catch (Exception $e) {
      error_log("MailService setup failed: {$e->getMessage()}");
    }
  }

  public function sendEmail(string $to, string $subject, string $body): bool
  {
    try {
      $this->mailer->addAddress($to); 

      $this->mailer->isHTML(true); 
      $this->mailer->Subject = $subject;
      $this->mailer->Body    = $body;

      $this->mailer->send();
      return true;
    } catch (Exception $e) {
      error_log("Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
      return false;
    }
  }
}
