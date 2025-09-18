<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\UserRepository;
use App\Repositories\AttendanceRepository;

class ScannerController extends Controller
{
    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function scannerDisplay()
    {
        $this->view("scanner/attendance", [
            "title" => "Scanner"
        ], false);
    }

    public function scanQR()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentNumber = $_POST['qrCodeValue'] ?? null;

            if ($studentNumber) {
                $user = $this->userRepo->findByIdentifier($studentNumber);

                if ($user && $user['role'] === 'student' && isset($user['student_id'])) {
                    $attendanceRepo = new AttendanceRepository();
                    $success = $attendanceRepo->logAttendance((int)$user['student_id'], 'qr');

                    if ($success) {
                        // Gumamit ng JS para mag alert at bumalik sa scanner page
                        echo "<script>
                                alert('✅ Successful Scan!\\nName: " . addslashes($user['full_name']) . " \\nStudent Number: " . addslashes($user['student_number']) . "');
                                window.location.href = '/libsys/public/scanner/attendance';
                              </script>";
                    } else {
                        echo "<script>
                                alert('❌ Failed to log attendance. Please try again.');
                                window.location.href = '/libsys/public/scanner/attendance';
                              </script>";
                    }
                } else {
                    echo "<script>
                            alert('⚠️ Invalid student number or not a student.');
                            window.location.href = '/libsys/public/scanner/attendance';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('⚠️ QR code value missing.');
                        window.location.href = '/libsys/public/scanner/attendance';
                      </script>";
            }
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
    }

    //manual entry
    public function manualEntry() {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $studentNumber = $_POST['studentNumber'] ?? null;
          $studentName = $_POST['studentName'] ?? null;

          if ($studentNumber && $studentName) {
              $user = $this->userRepo->findByIdentifier($studentNumber);

              if ($user && $user['role'] === 'student' && isset($user['student_id'])) {
                  $attendanceRepo = new AttendanceRepository();
                  $success = $attendanceRepo->logAttendance((int)$user['student_id'], 'manual');

                  if ($success) {
                      echo "<script>
                              alert('✅ Manual attendance recorded!\\nName: " . addslashes($user['full_name']) . " \\nStudent Number: " . addslashes($user['student_number']) . "');
                              window.location.href = '/libsys/public/scanner/attendance';
                            </script>";
                  } else {
                      echo "<script>
                              alert('❌ Failed to log attendance.');
                              window.location.href = '/libsys/public/scanner/attendance';
                            </script>";
                  }
              } else {
                  echo "<script>
                          alert('⚠️ Student not found or not a student.');
                          window.location.href = '/libsys/public/scanner/attendance';
                        </script>";
              }
          } else {
              echo "<script>
                      alert('⚠️ Please fill out all fields.');
                      window.location.href = '/libsys/public/scanner/attendance';
                    </script>";
          }
      } else {
          http_response_code(405);
          header("Location: /libsys/src/Views/errors/405.php");
      }
    }
}
