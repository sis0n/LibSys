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
      $this->mailer->clearCCs();
      $this->mailer->clearBCCs();
      $this->mailer->clearAttachments();

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

  public function sendOverdueNotice($toEmail, $borrowerName, $bookTitle, $dueDate)
  {
    $subject = 'ACTION REQUIRED: Overdue Book Notice - ' . $bookTitle;

    $body = "
            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;'>
                <div style='background-color: #c53030; color: white; padding: 20px; text-align: center;'>
                    <h2 style='margin: 0;'>Overdue Book Notice</h2>
                </div>
                <div style='padding: 20px;'>
                    <p>Hi <strong>$borrowerName</strong>,</p>
                    <p>Our records indicate that you have a book that is past its return date. Please return it as soon as possible to avoid further penalties.</p>
                    
                    <div style='background-color: #fff5f5; border-left: 4px solid #c53030; padding: 15px; margin: 20px 0;'>
                        <p style='margin: 5px 0;'><strong>Book Title:</strong> $bookTitle</p>
                        <p style='margin: 5px 0;'><strong>Due Date:</strong> <span style='color: #c53030;'>$dueDate</span></p>
                    </div>

                    <p>If you have already returned this book, please disregard this notice or contact the librarian.</p>
                    <br>
                    <p style='font-size: 0.9em; color: #666;'>Thank you,<br>LibSys Automailer</p>
                </div>
                <div style='background-color: #f7fafc; padding: 15px; text-align: center; font-size: 0.8em; color: #718096;'>
                    This is an automated message. Please do not reply directly to this email.
                </div>
            </div>
        ";

    return $this->sendEmail($toEmail, $subject, $body);
  }
}
