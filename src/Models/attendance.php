<?php

namespace App\Models;

class Attendance{
  public int $studentId;
  public string $studentNumber;
  public string $fullName;
  public string $yearLevel;
  public string $course;
  public string $method;
  public string $timestamp;

  public function __construct(
    int $studentId,
    string $studentNumber,
    string $fullName,
    string $yearLevel,
    string $course,
    string $method = 'qr',
    ?string $timestamp = null
  ) {
    $this->studentId = $studentId;
    $this->studentNumber = $studentNumber;
    $this->fullName = $fullName;
    $this->yearLevel = $yearLevel;
    $this->course = $course;
    $this->method = $method;
    $this->timestamp = $timestamp ?? date("Y-m-d H:i:s");
  }
}
