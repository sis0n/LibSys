<?php

namespace App\Models;

class Attendance
{
    private int $user_id;
    private string $student_number;
    private string $full_name;
    private string $year_level;
    private string $course;
    private string $source;     // qr o manual
    private string $timestamp;

    public function __construct(
        int $user_id,
        string $student_number,
        string $full_name,
        string $year_level,
        string $course,
        string $source,
        string $timestamp
    ) {
        $this->user_id = $user_id;
        $this->student_number = $student_number;
        $this->full_name = $full_name;
        $this->year_level = $year_level;
        $this->course = $course;
        $this->source = $source;
        $this->timestamp = $timestamp;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getStudentNumber(): string
    {
        return $this->student_number;
    }

    public function getFullName(): string
    {
        return $this->full_name;
    }

    public function getYearLevel(): string
    {
        return $this->year_level;
    }

    public function getCourse(): string
    {
        return $this->course;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }
    public function getCreatedAt(): string
    {
        return $this->timestamp;
    }

}