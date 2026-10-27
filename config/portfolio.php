<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Personal Identity & Professional Brand
    |--------------------------------------------------------------------------
    */
    'brand' => [
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
        'short_bio' => "I'm Hasnain Al Ifan, a Junior Software Engineer at APPIFLY BD LIMITED and a final-year Computer Science and Engineering student at BUBT. I work on practical web applications, with hands-on experience in Laravel, PHP, database-driven systems, application workflows, integrations, testing, and debugging. My academic projects have also given me experience with mobile development, full-stack applications, and AI-based computer vision.",
        'detailed_bio' => [
            "I'm a final-year Computer Science and Engineering student at Bangladesh University of Business and Technology (BUBT) and a Junior Software Engineer at APPIFLY BD LIMITED in Dhaka.",
            "My professional work focuses on web application development. I have independently developed DoctorQR, a doctor-focused digital practice platform that brings together appointment-related workflows, payment status management, queue handling, SMS notifications, video consultation, and digital prescriptions based on requirements communicated by the company.",
            "I enjoy understanding how complete applications work—from database schema and backend application logic to user-facing interfaces and third-party integrations. My work consistently involves developing features, testing workflows, debugging edge cases, and ensuring smooth user interactions.",
            "Through university and academic projects, I have also explored desktop software in C++, e-commerce web applications, React Native mobile apps, and deep learning for biomedical image analysis. I am committed to solidifying my software engineering fundamentals while building dependable software that solves tangible problems."
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Profiles
    |--------------------------------------------------------------------------
    */
    'socials' => [
        'github' => [
            'name' => 'GitHub',
            'url' => 'https://github.com/ifan-001',
            'handle' => '@ifan-001',
            'icon' => 'github',
        ],
        'linkedin' => [
            'name' => 'LinkedIn',
            'url' => 'https://www.linkedin.com/in/hasnain-al-ifan/',
            'handle' => 'hasnain-al-ifan',
            'icon' => 'linkedin',
        ],
        'kaggle' => [
            'name' => 'Kaggle',
            'url' => 'https://www.kaggle.com/hasnainalifan',
            'handle' => 'hasnainalifan',
            'icon' => 'kaggle',
        ],
        'email' => [
            'name' => 'Email',
            'url' => 'mailto:hasnainalifan23@gmail.com',
            'handle' => 'hasnainalifan23@gmail.com',
            'icon' => 'mail',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resume Configuration
    |--------------------------------------------------------------------------
    */
    'resume' => [
        'path' => '/resume/Hasnain-Al-Ifan-CV.pdf',
        'is_available' => false, // Set to true once the final verified PDF is placed in public/resume
        'status_note' => 'Full CV available on request during current final-semester review.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Professional Focus Cards (About section)
    |--------------------------------------------------------------------------
    */
    'focus_areas' => [
        [
            'number' => '01',
            'title' => 'Software Engineering',
            'description' => 'Architecting clean, modular software systems with a focus on maintainability, readable code structure, and pragmatic problem-solving.',
        ],
        [
            'number' => '02',
            'title' => 'Backend & Web Applications',
            'description' => 'Developing database-backed web applications using PHP and Laravel, designing relational schemas, service modules, and secure controllers.',
        ],
        [
            'number' => '03',
            'title' => 'Integrations & Testing',
            'description' => 'Integrating third-party services including Agora RTC for real-time video, SMS gateways for notifications, and rigorously testing application workflows.',
        ],
        [
            'number' => '04',
            'title' => 'Mobile & AI Exploration',
            'description' => 'Applying React Native for cross-platform mobile apps and experimenting with deep learning computer vision models for medical image analysis.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Work Experience
    |--------------------------------------------------------------------------
    */
    'experience' => [
        [
            'company' => 'APPIFLY BD LIMITED',
            'location' => 'Dhaka, Bangladesh',
            'website' => 'https://appiflybd.com',
            'roles' => [
                [
                    'designation' => 'Junior Software Engineer',
                    'period' => 'Present',
                    'type' => 'Full-time',
                    'current' => true,
                    'summary' => 'Developing web-based software applications and implementing practical application workflows, third-party integrations, and user-facing features.',
                ],
                [
                    'designation' => 'Software Development Intern',
                    'period' => '20 June 2026 — 20 August 2026',
                    'type' => 'Internship',
                    'current' => false,
                    'summary' => 'Contributed to internal web application modules and Laravel systems including Employee Management System and foundational backend workflows.',
                ],
            ],
            'timeline_label' => '20 June 2026 — Present',
            'highlights' => [
                'Independently developed the DoctorQR digital practice web application from requirements provided by the company.',
                'Engineered backend logic, database schemas, and Blade frontend interfaces in PHP and Laravel.',
                'Implemented real-time video consultation workflows using Agora RTC Token Generator and WebRTC integration with custom Picture-in-Picture (PIP) support.',
                'Integrated SMS notification gateways (sms_net_bd) for instant patient serial issuance and consultation link delivery.',
                'Implemented appointment queue management with doctor-controlled patient calling and multi-state payment tracking (Pending, Partial Paid, Paid, Failed, Refunded).',
                'Performed systematic testing, debugging, speed optimization, and security hardening (XSS protection, CSP headers, audit logging).',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Projects Showcase
    |--------------------------------------------------------------------------
    */
    'projects' => [
        // Flagship Project
        [
            'id' => 'doctor-qr',
            'title' => 'DoctorQR — Doctor-Centric Digital Practice Platform',
            'slug' => 'doctor-qr',
            'category' => 'professional',
            'category_label' => 'Professional Web Application / SaaS',
            'featured' => true,
            'summary' => 'A comprehensive web-based digital practice platform that empowers individual doctors in Bangladesh to manage their digital practice, QR-based patient bookings, serials, queues, SMS alerts, Agora video consultations, and digital prescriptions.',
            'role' => 'Software Developer (Independently developed web application based on company requirements)',
            'context' => 'Developed at APPIFLY BD LIMITED as a full commercial web application.',
            'live_url' => 'https://doctor-qr.appiflybd.com/',
            'repo_url' => 'https://github.com/appifly-bd-ltd/doctor-qr-laravel',
            'tech_stack' => [
                'PHP 8.2+',
                'Laravel',
                'MySQL',
                'Blade',
                'JavaScript',
                'CSS3',
                'Agora RTC Token Generator',
                'SMS Gateway (sms_net_bd)',
                'Endroid QR Code',
                'Git & GitHub',
            ],
            'workflows' => [
                [
                    'name' => 'Doctor Digital Practice Page',
                    'icon' => 'user-check',
                    'desc' => 'Individual doctor-branded practice landing pages with verified BMDC credential displays, chamber locations, visit fees, and real-time appointment scheduling.',
                ],
                [
                    'name' => 'QR-Driven Booking Journey',
                    'icon' => 'qr-code',
                    'desc' => 'Prescription pads and chamber cards feature a scannable QR code directing patients directly to the doctor\'s personal booking flow with zero mobile app install required.',
                ],
                [
                    'name' => 'Serial & Queue Management',
                    'icon' => 'list-ordered',
                    'desc' => 'Automated serial generation, doctor-controlled live patient calling, queue status tracking, and dynamic patient flow control.',
                ],
                [
                    'name' => 'Payment Lifecycle Handling',
                    'icon' => 'credit-card',
                    'desc' => 'Strict 5-state payment model tracking consultations: Pending, Partial Paid, Paid, Failed, and Refunded, seamlessly connected to appointment activation.',
                ],
                [
                    'name' => 'Agora Video Consultation with PIP',
                    'icon' => 'video',
                    'desc' => 'Integrated Agora WebRTC consultation: Doctor initiates consultation → Secure token generated → SMS delivered to patient with meeting link → Patient joins room → Floating auto-PIP enabled on scroll.',
                ],
                [
                    'name' => 'SMS Notification Engine',
                    'icon' => 'message-square',
                    'desc' => 'Automated transactional SMS notifications via Alpha SMS gateway (sms_net_bd) providing instant booking confirmations, serial updates, and secure teleconsultation links.',
                ],
                [
                    'name' => 'Digital Prescription & Patient Records',
                    'icon' => 'file-text',
                    'desc' => 'Streamlined medical viewer, patient file uploads, digital prescription generation, and audit logging for security compliance.',
                ],
            ],
            'payment_states' => ['Pending', 'Partial Paid', 'Paid', 'Failed', 'Refunded'],
            'contribution_details' => [
                'Built the modular architecture across Booking, Scheduling, Payments, Practice, Prescription, Notifications, and Tenancy modules.',
                'Implemented Agora RTC token generation and integrated real-time video interface with picture-in-picture bubble handling.',
                'Created responsive Blade templates for doctor public landing pages, patient booking steps, and doctor operational dashboards.',
                'Tested end-to-end appointment workflows, resolved XSS vulnerabilities, implemented CSP headers, and optimized page load speed.',
            ],
        ],

        // Secondary Professional Project
        [
            'id' => 'employee-management',
            'title' => 'Employee Management System',
            'slug' => 'employee-management',
            'category' => 'professional',
            'category_label' => 'Professional Web Application / Laravel',
            'featured' => false,
            'summary' => 'A centralized organizational web application for managing employee profiles, staff serial numbering, avatars, departments, and administrative workflows.',
            'role' => 'Backend & Full-Stack Developer (Internship Project)',
            'context' => 'Built during the internship period at APPIFLY BD LIMITED to manage internal personnel records.',
            'live_url' => null,
            'repo_url' => 'https://github.com/ifan-001/employee-management-laravel',
            'tech_stack' => [
                'PHP 8.2+',
                'Laravel 12',
                'MySQL',
                'Laravel Breeze',
                'Blade',
                'CSS3',
                'JavaScript',
            ],
            'key_features' => [
                'Role-based authentication & profile control with Laravel Breeze',
                'Employee CRUD operations with avatar image uploading and storage handling',
                'Serial number assignment and directory indexing for structured records',
                'Database migrations, model relationships, and form request validation',
            ],
            'contribution_details' => [
                'Constructed database migrations for employees, profile avatars, and serial numbers.',
                'Implemented administrative dashboard controller and employee management interfaces.',
                'Applied Laravel form request validations and secure file storage protocols.',
            ],
        ],

        // Academic Mobile Project
        [
            'id' => 'c2c-android-app',
            'title' => 'C2C Android Marketplace Application',
            'slug' => 'c2c-android-app',
            'category' => 'mobile',
            'category_label' => 'Academic Project / Mobile App Development',
            'featured' => false,
            'summary' => 'A customer-to-customer mobile marketplace application enabling users to list items for sale, browse categories, manage media uploads, and interact directly.',
            'role' => 'Mobile & Backend Developer (Academic Team Project)',
            'context' => 'University-level software development project exploring cross-platform mobile architecture.',
            'live_url' => null,
            'repo_url' => null,
            'tech_stack' => [
                'React Native',
                'Node.js',
                'MongoDB',
                'Cloudinary API',
                'REST API',
            ],
            'key_features' => [
                'Mobile marketplace interface for direct customer-to-customer transactions',
                'Cloudinary cloud storage integration for product imagery and media management',
                'User-friendly product catalog browsing, item creation, and listing updates',
                'Mobile-first responsive layout tailored for Android devices',
            ],
            'contribution_details' => [
                'Built UI screens in React Native for listing products and managing catalog items.',
                'Connected mobile views with backend Node.js endpoints and Cloudinary API for media handling.',
            ],
        ],

        // Academic Full-Stack Web Project
        [
            'id' => 'c2c-web-system',
            'title' => 'C2C Web Application System',
            'slug' => 'c2c-web-system',
            'category' => 'web',
            'category_label' => 'Academic Project / Full-Stack Web Development',
            'featured' => false,
            'summary' => 'A full-stack e-commerce web platform supporting customer-to-customer product listing, search filters, secure authentication, and order workflows.',
            'role' => 'Full-Stack Developer (Academic Project)',
            'context' => 'Comprehensive academic project demonstrating the integration between frontend, backend, and NoSQL database.',
            'live_url' => null,
            'repo_url' => null,
            'tech_stack' => [
                'React.js',
                'Node.js',
                'Express.js',
                'MongoDB',
                'REST APIs',
                'JWT Authentication',
            ],
            'key_features' => [
                'Modular React frontend with real-time product search and category filtering',
                'Secure user registration, authentication, and session handling',
                'Order processing workflows linking buyer inquiries to seller listings',
                'Clean separation of concerns across API routes, controllers, and MongoDB collections',
            ],
            'contribution_details' => [
                'Implemented frontend components and responsive navigation in React.',
                'Designed RESTful API routes in Node/Express and mapped MongoDB document schemas.',
            ],
        ],

        // Academic Desktop Project
        [
            'id' => 'rental-solution',
            'title' => 'Rental Solution — Property & Tenant System',
            'slug' => 'rental-solution',
            'category' => 'desktop',
            'category_label' => 'Academic Project / Desktop & Database',
            'featured' => false,
            'summary' => 'A desktop-based rental management system developed in C++ with MySQL database integration to organize house-renting records, tenant agreements, and rent tracking.',
            'role' => 'System Design, C++ Development & Testing',
            'context' => 'University semester project presented at BUBT focusing on desktop software engineering and relational persistence.',
            'live_url' => null,
            'repo_url' => null,
            'tech_stack' => [
                'C++',
                'MySQL',
                'Relational DB Design',
                'Desktop UI',
            ],
            'key_features' => [
                'Tenant record management and lease agreement tracking',
                'Property unit availability and monthly rent collection logging',
                'SQL database integration with parameterized queries and relational integrity',
                'Structured desktop reporting workflow tested for academic presentation',
            ],
            'contribution_details' => [
                'Contributed to system architectural design, database modeling, and C++ application modules.',
                'Conducted functionality testing and validation for the final academic project defense.',
            ],
        ],

        // AI & Computer Vision Capstone
        [
            'id' => 'nuclei-segmentation',
            'title' => 'Cell Nuclei Segmentation with Deep Learning',
            'slug' => 'nuclei-segmentation',
            'category' => 'ai',
            'category_label' => 'Academic Capstone / AI & Computer Vision',
            'featured' => false,
            'summary' => 'An academic capstone project investigating automated biomedical image analysis, applying convolutional neural networks for precise cell nuclei detection and semantic segmentation.',
            'role' => 'Machine Learning & Computer Vision Student Researcher',
            'context' => 'Academic capstone project at BUBT exploring deep learning techniques applied to medical image analysis.',
            'live_url' => null,
            'repo_url' => null,
            'tech_stack' => [
                'Python',
                'OpenCV',
                'TensorFlow',
                'Keras',
                'Deep Learning / CNN',
                'Biomedical Preprocessing',
            ],
            'key_features' => [
                'Image preprocessing pipeline: color normalization, thresholding, and augmentation',
                'Deep learning architecture trained for pixel-wise semantic nuclei segmentation',
                'Evaluation using visual overlay comparison and segmentation metric analysis',
                'Distinguished academic inquiry into applying computer vision to healthcare dilemmas',
            ],
            'contribution_details' => [
                'Preprocessed histological microscopic imagery using Python and OpenCV.',
                'Configured training loops and evaluated model segmentation predictions in TensorFlow/Keras.',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Technical Skills
    | Categorized by verified practical experience
    |--------------------------------------------------------------------------
    */
    'skill_categories' => [
        [
            'title' => 'Backend & Web Development',
            'description' => 'Core technologies applied in production and full-stack projects',
            'skills' => [
                ['name' => 'PHP', 'context' => 'Used in professional projects'],
                ['name' => 'Laravel', 'context' => 'Used in professional projects'],
                ['name' => 'JavaScript', 'context' => 'Used in professional projects'],
                ['name' => 'Blade Templates', 'context' => 'Used in professional projects'],
                ['name' => 'HTML5 & CSS3', 'context' => 'Used in professional projects'],
                ['name' => 'REST API Development', 'context' => 'Used in professional & academic projects'],
                ['name' => 'Web Application Workflows', 'context' => 'Used in professional projects'],
            ],
        ],
        [
            'title' => 'Mobile Development',
            'description' => 'Cross-platform mobile technologies applied in university projects',
            'skills' => [
                ['name' => 'React Native', 'context' => 'Used in academic projects'],
                ['name' => 'Expo', 'context' => 'Used in academic projects'],
                ['name' => 'Expo Router', 'context' => 'Used in personal/academic exploration'],
            ],
        ],
        [
            'title' => 'Databases & Storage',
            'description' => 'Relational and document storage solutions',
            'skills' => [
                ['name' => 'MySQL', 'context' => 'Used in professional & academic projects'],
                ['name' => 'SQLite', 'context' => 'Used in local testing & development'],
                ['name' => 'MongoDB', 'context' => 'Used in academic web & mobile projects'],
            ],
        ],
        [
            'title' => 'Programming Languages',
            'description' => 'Languages studied throughout academic and software engineering work',
            'skills' => [
                ['name' => 'PHP', 'context' => 'Primary professional language'],
                ['name' => 'Python', 'context' => 'Used in AI & academic capstone'],
                ['name' => 'C / C++', 'context' => 'Used in university foundation & desktop project'],
                ['name' => 'Java', 'context' => 'Used in academic coursework'],
                ['name' => 'SQL', 'context' => 'Used in relational database queries'],
            ],
        ],
        [
            'title' => 'Integrations & External APIs',
            'description' => 'Production SDKs and third-party services connected in applications',
            'skills' => [
                ['name' => 'Agora Video Calling RTC', 'context' => 'Used in DoctorQR production app'],
                ['name' => 'SMS Gateway (sms_net_bd)', 'context' => 'Used in DoctorQR production app'],
                ['name' => 'Cloudinary API', 'context' => 'Used in C2C mobile project'],
                ['name' => 'QR Code Generation (Endroid)', 'context' => 'Used in DoctorQR production app'],
            ],
        ],
        [
            'title' => 'Tools & Development Environment',
            'description' => 'Day-to-day workflow and version control tooling',
            'skills' => [
                ['name' => 'Git & GitHub', 'context' => 'Daily version control & collaboration'],
                ['name' => 'VS Code', 'context' => 'Primary code editor'],
                ['name' => 'XAMPP & Apache', 'context' => 'Local PHP/MySQL runtime environment'],
                ['name' => 'Postman', 'context' => 'API testing and validation'],
            ],
        ],
        [
            'title' => 'Engineering Practices',
            'description' => 'Methodologies practiced across application cycles',
            'skills' => [
                ['name' => 'Authentication & Authorization', 'context' => 'Implemented in Laravel & Express'],
                ['name' => 'Database-Driven Workflows', 'context' => 'Schema design, migrations, and queries'],
                ['name' => 'Form Validation & Sanitization', 'context' => 'Strict request validation & XSS protection'],
                ['name' => 'Application Testing & Debugging', 'context' => 'Workflow verification and issue resolution'],
                ['name' => 'UI Implementation', 'context' => 'Responsive, touch-friendly, accessible views'],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Education History
    |--------------------------------------------------------------------------
    */
    'education' => [
        [
            'degree' => 'Bachelor of Science in Computer Science and Engineering',
            'institution' => 'Bangladesh University of Business and Technology (BUBT)',
            'location' => 'Dhaka, Bangladesh',
            'status' => 'Final-year Student (8th Semester)',
            'cgpa' => '2.86 / 4.00',
            'expected_graduation' => 'December 2026',
            'schedule_note' => 'Subject to confirmation of the university\'s academic schedule',
            'is_primary' => true,
            'coursework' => [
                'Data Structures',
                'Algorithms',
                'Database Systems',
                'Computer Networks',
                'Operating Systems',
                'Web Technologies',
                'Artificial Intelligence',
                'Cyber Security',
                'Digital Forensics',
                'Software Engineering',
            ],
        ],
        [
            'degree' => 'Higher Secondary Certificate (HSC)',
            'institution' => 'Govt. Abdul Jabbar College, Bhola',
            'location' => 'Bhola, Bangladesh',
            'group' => 'Science',
            'gpa' => '4.50 / 5.00',
            'passing_year' => '2021',
            'is_primary' => false,
        ],
        [
            'degree' => 'Secondary School Certificate (SSC)',
            'institution' => 'Borhanuddin Govt. High School, Bhola',
            'location' => 'Bhola, Bangladesh',
            'group' => 'Science',
            'gpa' => '4.63 / 5.00',
            'passing_year' => '2019',
            'is_primary' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Research & Technical Interests ("Beyond Application Development")
    |--------------------------------------------------------------------------
    */
    'research' => [
        'intro' => "Alongside software engineering, I'm interested in exploring how intelligent technologies can be applied to complex real-world problems.",
        'note' => 'These reflect academic curiosity and capstone exploration rather than commercial research positions.',
        'interests' => [
            [
                'title' => 'Artificial Intelligence & Deep Learning',
                'icon' => 'cpu',
                'description' => 'Studying neural network architectures, model training dynamics, and how machine intelligence can integrate into real-time software systems.',
            ],
            [
                'title' => 'Computer Vision & Medical Image Analysis',
                'icon' => 'scan',
                'description' => 'Exploring automated biomedical image processing, segmentation methods, and feature extraction from microscopic cell imagery.',
            ],
            [
                'title' => 'Cyber Security & Digital Forensics',
                'icon' => 'shield-check',
                'description' => 'Understanding network defense, web security vulnerabilities, secure authentication mechanisms, and digital forensics principles.',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Evidence-Based Highlights
    |--------------------------------------------------------------------------
    */
    'highlights' => [
        [
            'label' => 'Current Role',
            'value' => 'Junior Software Engineer',
            'detail' => 'APPIFLY BD LIMITED, Dhaka',
        ],
        [
            'label' => 'Flagship Delivery',
            'value' => 'DoctorQR Platform',
            'detail' => 'End-to-end Laravel web app with live production deployment',
        ],
        [
            'label' => 'Production Integrations',
            'value' => 'Agora RTC & SMS Gateways',
            'detail' => 'Real-time video consultation & transactional SMS',
        ],
        [
            'label' => 'Academic Standing',
            'value' => 'B.Sc. CSE Final Year',
            'detail' => 'BUBT (Expected completion December 2026)',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO & Metadata
    |--------------------------------------------------------------------------
    */
    'meta' => [
        'title' => 'Hasnain Al Ifan | Junior Software Engineer',
        'description' => 'Explore the software development experience, Laravel web applications, technical projects, and academic interests of Hasnain Al Ifan, a Junior Software Engineer at APPIFLY BD LIMITED.',
        'keywords' => 'Hasnain Al Ifan, Junior Software Engineer, Web Developer, Laravel Developer, PHP, DoctorQR, Appifly BD Limited, BUBT, Dhaka, Bangladesh',
        'author' => 'Hasnain Al Ifan',
        'canonical_url' => 'http://localhost:8000',
    ],
];
