<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'detailed_bio' => 'array',
        'resume_available' => 'boolean',
    ];

    /**
     * Get or create the singleton profile record.
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'short_name' => 'IFAN.',
            'full_name' => 'Hasnain Al Ifan',
            'title' => 'Junior Software Engineer',
            'sub_title' => 'Web Application Development | Backend Engineering',
            'location' => 'Mirpur-2, Dhaka, Bangladesh',
            'email' => 'hasnainalifan23@gmail.com',
            'phone' => '+8801608564447',
            'profile_image' => '/images/profile.jpg',
            'avatar_fallback' => 'HI',
            'positioning' => 'Junior Software Engineer building practical web applications and exploring mobile and intelligent software systems.',
            'hero_headline' => 'Building Software That Solves Real Problems.',
            'hero_subheadline' => 'Web Applications. Backend Systems. Practical Engineering.',
            'short_bio' => "I'm Hasnain Al Ifan, a Junior Software Engineer at APPIFLY BD LIMITED and a final-year Computer Science and Engineering student at BUBT.",
            'detailed_bio' => [
                "I'm a final-year Computer Science and Engineering student at Bangladesh University of Business and Technology (BUBT) and a Junior Software Engineer at APPIFLY BD LIMITED in Dhaka.",
                "My professional work focuses on web application development. I have independently developed DoctorQR, a doctor-focused digital practice platform based on company requirements.",
                "I enjoy understanding how complete applications work—from database schema and backend application logic to user-facing interfaces and third-party integrations.",
                "Through academic projects, I have also explored desktop software in C++, e-commerce web applications, React Native mobile apps, and deep learning for biomedical image analysis."
            ],
            'resume_path' => '/resume/Hasnain-Al-Ifan-CV.pdf',
            'resume_available' => true,
            'resume_status_note' => 'Official CV available for preview and download.',
        ]);
    }
}
