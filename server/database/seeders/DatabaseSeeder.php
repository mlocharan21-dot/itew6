<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Course;
use App\Models\Section;
use App\Models\Room;
use App\Models\Laboratory;
use App\Models\Event;
use App\Models\Syllabus;
use App\Models\Lesson;
use App\Models\Curriculum;
use App\Models\Schedule;
use App\Models\FacultyAssignment;
use App\Models\StudentAcademicHistory;
use App\Models\StudentExtraCurricular;
use App\Models\StudentViolation;
use App\Models\StudentSkill;
use App\Models\StudentOrganization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
        ]);

        // Students (IDs are integers: 2600001–2600020)
        $students = [
            ['id' => 2600001, 'first_name' => 'John', 'last_name' => 'Smith', 'email' => 'john.smith@ccs.edu', 'phone' => '09123456789', 'year' => 3, 'section' => 'A', 'status' => 'active', 'address' => '123 Main St, City', 'birthday' => '2002-05-15', 'enrolled_date' => '2021-06-01'],
            ['id' => 2600002, 'first_name' => 'Maria', 'last_name' => 'Garcia', 'email' => 'maria.garcia@ccs.edu', 'phone' => '09123456790', 'year' => 2, 'section' => 'B', 'status' => 'active', 'address' => '456 Oak Ave, City', 'birthday' => '2003-08-22', 'enrolled_date' => '2022-06-01'],
            ['id' => 2600003, 'first_name' => 'David', 'last_name' => 'Lee', 'email' => 'david.lee@ccs.edu', 'phone' => '09123456791', 'year' => 4, 'section' => 'A', 'status' => 'active', 'address' => '789 Pine Rd, City', 'birthday' => '2001-03-10', 'enrolled_date' => '2020-06-01'],
            ['id' => 2600004, 'first_name' => 'Sarah', 'last_name' => 'Johnson', 'email' => 'sarah.johnson@ccs.edu', 'phone' => '09123456792', 'year' => 1, 'section' => 'C', 'status' => 'active', 'address' => '321 Elm St, City', 'birthday' => '2004-11-28', 'enrolled_date' => '2023-06-01'],
            ['id' => 2600005, 'first_name' => 'Michael', 'last_name' => 'Brown', 'email' => 'michael.brown@ccs.edu', 'phone' => '09123456793', 'year' => 2, 'section' => 'A', 'status' => 'active', 'address' => '654 Maple Dr, City', 'birthday' => '2003-02-14', 'enrolled_date' => '2022-06-01'],
            ['id' => 2600006, 'first_name' => 'Emily', 'last_name' => 'Davis', 'email' => 'emily.davis@ccs.edu', 'phone' => '09123456794', 'year' => 3, 'section' => 'B', 'status' => 'active', 'address' => '987 Cedar Ln, City', 'birthday' => '2002-07-03', 'enrolled_date' => '2021-06-01'],
            ['id' => 2600007, 'first_name' => 'James', 'last_name' => 'Wilson', 'email' => 'james.wilson@ccs.edu', 'phone' => '09123456795', 'year' => 4, 'section' => 'C', 'status' => 'active', 'address' => '147 Birch Ave, City', 'birthday' => '2001-12-19', 'enrolled_date' => '2020-06-01'],
            ['id' => 2600008, 'first_name' => 'Jennifer', 'last_name' => 'Martinez', 'email' => 'jennifer.martinez@ccs.edu', 'phone' => '09123456796', 'year' => 1, 'section' => 'A', 'status' => 'active', 'address' => '258 Spruce Rd, City', 'birthday' => '2004-04-05', 'enrolled_date' => '2023-06-01'],
            ['id' => 2600009, 'first_name' => 'Robert', 'last_name' => 'Anderson', 'email' => 'robert.anderson@ccs.edu', 'phone' => '09123456797', 'year' => 2, 'section' => 'C', 'status' => 'inactive', 'address' => '369 Willow St, City', 'birthday' => '2003-09-11', 'enrolled_date' => '2022-06-01'],
            ['id' => 2600010, 'first_name' => 'Lisa', 'last_name' => 'Taylor', 'email' => 'lisa.taylor@ccs.edu', 'phone' => '09123456798', 'year' => 3, 'section' => 'A', 'status' => 'active', 'address' => '741 Ash Dr, City', 'birthday' => '2002-01-25', 'enrolled_date' => '2021-06-01'],
            ['id' => 2600011, 'first_name' => 'Christopher', 'last_name' => 'Thomas', 'email' => 'christopher.thomas@ccs.edu', 'phone' => '09123456799', 'year' => 4, 'section' => 'B', 'status' => 'active', 'address' => '852 Poplar Ln, City', 'birthday' => '2001-06-08', 'enrolled_date' => '2020-06-01'],
            ['id' => 2600012, 'first_name' => 'Amanda', 'last_name' => 'Jackson', 'email' => 'amanda.jackson@ccs.edu', 'phone' => '09123456800', 'year' => 1, 'section' => 'B', 'status' => 'active', 'address' => '963 Walnut Ave, City', 'birthday' => '2004-10-17', 'enrolled_date' => '2023-06-01'],
            ['id' => 2600013, 'first_name' => 'Daniel', 'last_name' => 'White', 'email' => 'daniel.white@ccs.edu', 'phone' => '09123456801', 'year' => 2, 'section' => 'A', 'status' => 'active', 'address' => '159 Cherry Rd, City', 'birthday' => '2003-03-22', 'enrolled_date' => '2022-06-01'],
            ['id' => 2600014, 'first_name' => 'Michelle', 'last_name' => 'Harris', 'email' => 'michelle.harris@ccs.edu', 'phone' => '09123456802', 'year' => 3, 'section' => 'C', 'status' => 'active', 'address' => '753 Beech St, City', 'birthday' => '2002-08-30', 'enrolled_date' => '2021-06-01'],
            ['id' => 2600015, 'first_name' => 'Kevin', 'last_name' => 'Martin', 'email' => 'kevin.martin@ccs.edu', 'phone' => '09123456803', 'year' => 4, 'section' => 'A', 'status' => 'active', 'address' => '486 Fir Dr, City', 'birthday' => '2001-11-12', 'enrolled_date' => '2020-06-01'],
            ['id' => 2600016, 'first_name' => 'Jessica', 'last_name' => 'Thompson', 'email' => 'jessica.thompson@ccs.edu', 'phone' => '09123456804', 'year' => 1, 'section' => 'C', 'status' => 'active', 'address' => '357 Redwood Ln, City', 'birthday' => '2004-02-28', 'enrolled_date' => '2023-06-01'],
            ['id' => 2600017, 'first_name' => 'Brian', 'last_name' => 'Garcia', 'email' => 'brian.garcia@ccs.edu', 'phone' => '09123456805', 'year' => 2, 'section' => 'B', 'status' => 'active', 'address' => '246 Sequoia Ave, City', 'birthday' => '2003-07-14', 'enrolled_date' => '2022-06-01'],
            ['id' => 2600018, 'first_name' => 'Nicole', 'last_name' => 'Robinson', 'email' => 'nicole.robinson@ccs.edu', 'phone' => '09123456806', 'year' => 3, 'section' => 'A', 'status' => 'active', 'address' => '135 Cypress Rd, City', 'birthday' => '2002-12-03', 'enrolled_date' => '2021-06-01'],
            ['id' => 2600019, 'first_name' => 'Steven', 'last_name' => 'Clark', 'email' => 'steven.clark@ccs.edu', 'phone' => '09123456807', 'year' => 4, 'section' => 'B', 'status' => 'inactive', 'address' => '864 Magnolia St, City', 'birthday' => '2001-05-20', 'enrolled_date' => '2020-06-01'],
            ['id' => 2600020, 'first_name' => 'Stephanie', 'last_name' => 'Rodriguez', 'email' => 'stephanie.rodriguez@ccs.edu', 'phone' => '09123456808', 'year' => 1, 'section' => 'A', 'status' => 'active', 'address' => '975 Hickory Dr, City', 'birthday' => '2004-09-07', 'enrolled_date' => '2023-06-01'],
        ];
        foreach ($students as $s) {
            Student::create($s);
        }

        // Faculty
        $faculty = [
            ['id' => 'FAC-001', 'first_name' => 'Dr. Robert', 'last_name' => 'Mendoza', 'email' => 'r.mendoza@ccs.edu', 'phone' => '09123456001', 'department' => 'Computer Science', 'position' => 'Professor', 'specialization' => 'Artificial Intelligence', 'office' => 'Room 301', 'status' => 'active', 'birthday' => '1975-03-15', 'hired_date' => '2010-08-01'],
            ['id' => 'FAC-002', 'first_name' => 'Dr. Maria', 'last_name' => 'Santos', 'email' => 'm.santos@ccs.edu', 'phone' => '09123456002', 'department' => 'Computer Science', 'position' => 'Associate Professor', 'specialization' => 'Database Systems', 'office' => 'Room 302', 'status' => 'active', 'birthday' => '1980-07-22', 'hired_date' => '2012-01-15'],
            ['id' => 'FAC-003', 'first_name' => 'Engr. James', 'last_name' => 'Cruz', 'email' => 'j.cruz@ccs.edu', 'phone' => '09123456003', 'department' => 'Information Technology', 'position' => 'Assistant Professor', 'specialization' => 'Network Security', 'office' => 'Room 201', 'status' => 'active', 'birthday' => '1985-11-08', 'hired_date' => '2015-06-01'],
            ['id' => 'FAC-004', 'first_name' => 'Dr. Elena', 'last_name' => 'Reyes', 'email' => 'e.reyes@ccs.edu', 'phone' => '09123456004', 'department' => 'Computer Science', 'position' => 'Professor', 'specialization' => 'Machine Learning', 'office' => 'Room 303', 'status' => 'active', 'birthday' => '1978-02-14', 'hired_date' => '2008-01-10'],
            ['id' => 'FAC-005', 'first_name' => 'Engr. Michael', 'last_name' => 'Torres', 'email' => 'm.torres@ccs.edu', 'phone' => '09123456005', 'department' => 'Information Technology', 'position' => 'Lecturer', 'specialization' => 'Web Development', 'office' => 'Room 202', 'status' => 'active', 'birthday' => '1990-06-30', 'hired_date' => '2018-08-01'],
            ['id' => 'FAC-006', 'first_name' => 'Dr. Sarah', 'last_name' => 'Lim', 'email' => 's.lim@ccs.edu', 'phone' => '09123456006', 'department' => 'Computer Science', 'position' => 'Associate Professor', 'specialization' => 'Software Engineering', 'office' => 'Room 304', 'status' => 'active', 'birthday' => '1982-09-18', 'hired_date' => '2011-03-20'],
            ['id' => 'FAC-007', 'first_name' => 'Engr. David', 'last_name' => 'Aquino', 'email' => 'd.aquino@ccs.edu', 'phone' => '09123456007', 'department' => 'Information Technology', 'position' => 'Assistant Professor', 'specialization' => 'Cloud Computing', 'office' => 'Room 203', 'status' => 'active', 'birthday' => '1988-04-25', 'hired_date' => '2016-01-15'],
            ['id' => 'FAC-008', 'first_name' => 'Dr. Patricia', 'last_name' => 'Villanueva', 'email' => 'p.villanueva@ccs.edu', 'phone' => '09123456008', 'department' => 'Computer Science', 'position' => 'Professor', 'specialization' => 'Data Science', 'office' => 'Room 305', 'status' => 'active', 'birthday' => '1976-12-05', 'hired_date' => '2009-08-01'],
            ['id' => 'FAC-009', 'first_name' => 'Engr. John', 'last_name' => 'Bautista', 'email' => 'j.bautista@ccs.edu', 'phone' => '09123456009', 'department' => 'Information Technology', 'position' => 'Lecturer', 'specialization' => 'Mobile Development', 'office' => 'Room 204', 'status' => 'active', 'birthday' => '1992-01-12', 'hired_date' => '2020-06-01'],
            ['id' => 'FAC-010', 'first_name' => 'Dr. Catherine', 'last_name' => 'Diaz', 'email' => 'c.diaz@ccs.edu', 'phone' => '09123456010', 'department' => 'Computer Science', 'position' => 'Associate Professor', 'specialization' => 'Computer Graphics', 'office' => 'Room 306', 'status' => 'inactive', 'birthday' => '1983-08-28', 'hired_date' => '2013-01-10'],
        ];
        foreach ($faculty as $f) {
            Faculty::create($f);
        }

        // Courses
        $courses = [
            ['id' => 'CS101', 'name' => 'Introduction to Computing', 'units' => 3, 'description' => 'Fundamentals of computing and programming', 'department' => 'Computer Science'],
            ['id' => 'CS102', 'name' => 'Computer Programming 1', 'units' => 3, 'description' => 'Introduction to programming using Python', 'department' => 'Computer Science'],
            ['id' => 'CS201', 'name' => 'Computer Programming 2', 'units' => 3, 'description' => 'Object-oriented programming with Java', 'department' => 'Computer Science'],
            ['id' => 'CS301', 'name' => 'Data Structures and Algorithms', 'units' => 3, 'description' => 'Fundamental data structures and algorithms', 'department' => 'Computer Science'],
            ['id' => 'CS302', 'name' => 'Database Management Systems', 'units' => 3, 'description' => 'Design and implementation of databases', 'department' => 'Computer Science'],
            ['id' => 'CS303', 'name' => 'Software Engineering', 'units' => 3, 'description' => 'Software development lifecycle', 'department' => 'Computer Science'],
            ['id' => 'CS304', 'name' => 'Operating Systems', 'units' => 3, 'description' => 'OS concepts and implementation', 'department' => 'Computer Science'],
            ['id' => 'CS401', 'name' => 'Artificial Intelligence', 'units' => 3, 'description' => 'Introduction to AI concepts and applications', 'department' => 'Computer Science'],
            ['id' => 'CS402', 'name' => 'Machine Learning', 'units' => 3, 'description' => 'Machine learning algorithms and techniques', 'department' => 'Computer Science'],
            ['id' => 'CS403', 'name' => 'Data Science', 'units' => 3, 'description' => 'Data analysis and visualization', 'department' => 'Computer Science'],
            ['id' => 'IT101', 'name' => 'IT Fundamentals', 'units' => 3, 'description' => 'Basic IT concepts and practices', 'department' => 'Information Technology'],
            ['id' => 'IT201', 'name' => 'Network Fundamentals', 'units' => 3, 'description' => 'Introduction to computer networks', 'department' => 'Information Technology'],
            ['id' => 'IT301', 'name' => 'Web Development', 'units' => 3, 'description' => 'Modern web development technologies', 'department' => 'Information Technology'],
            ['id' => 'IT302', 'name' => 'Cybersecurity', 'units' => 3, 'description' => 'Security principles and practices', 'department' => 'Information Technology'],
            ['id' => 'IT401', 'name' => 'Cloud Computing', 'units' => 3, 'description' => 'Cloud services and deployment', 'department' => 'Information Technology'],
        ];
        foreach ($courses as $c) {
            Course::create($c);
        }

        // Sections
        $sections = [
            ['id' => 'SEC-001', 'name' => 'Section A', 'year' => 1, 'semester' => '1st', 'capacity' => 40],
            ['id' => 'SEC-002', 'name' => 'Section B', 'year' => 1, 'semester' => '1st', 'capacity' => 40],
            ['id' => 'SEC-003', 'name' => 'Section C', 'year' => 1, 'semester' => '1st', 'capacity' => 40],
            ['id' => 'SEC-004', 'name' => 'Section A', 'year' => 2, 'semester' => '1st', 'capacity' => 35],
            ['id' => 'SEC-005', 'name' => 'Section B', 'year' => 2, 'semester' => '1st', 'capacity' => 35],
            ['id' => 'SEC-006', 'name' => 'Section A', 'year' => 3, 'semester' => '1st', 'capacity' => 30],
            ['id' => 'SEC-007', 'name' => 'Section B', 'year' => 3, 'semester' => '1st', 'capacity' => 30],
            ['id' => 'SEC-008', 'name' => 'Section A', 'year' => 4, 'semester' => '1st', 'capacity' => 25],
            ['id' => 'SEC-009', 'name' => 'Section B', 'year' => 4, 'semester' => '1st', 'capacity' => 25],
            ['id' => 'SEC-010', 'name' => 'Section C', 'year' => 4, 'semester' => '1st', 'capacity' => 25],
        ];
        foreach ($sections as $s) {
            Section::create($s);
        }

        // Rooms
        $rooms = [
            ['id' => 'RM-101', 'name' => 'Room 101', 'type' => 'Classroom', 'capacity' => 40, 'building' => 'Main Building'],
            ['id' => 'RM-102', 'name' => 'Room 102', 'type' => 'Classroom', 'capacity' => 40, 'building' => 'Main Building'],
            ['id' => 'RM-103', 'name' => 'Room 103', 'type' => 'Classroom', 'capacity' => 35, 'building' => 'Main Building'],
            ['id' => 'RM-201', 'name' => 'Room 201', 'type' => 'Classroom', 'capacity' => 30, 'building' => 'Main Building'],
            ['id' => 'RM-202', 'name' => 'Room 202', 'type' => 'Classroom', 'capacity' => 30, 'building' => 'Main Building'],
            ['id' => 'RM-301', 'name' => 'Room 301', 'type' => 'Lecture Hall', 'capacity' => 60, 'building' => 'Main Building'],
            ['id' => 'RM-302', 'name' => 'Room 302', 'type' => 'Lecture Hall', 'capacity' => 60, 'building' => 'Main Building'],
            ['id' => 'RM-401', 'name' => 'Room 401', 'type' => 'Seminar Room', 'capacity' => 20, 'building' => 'Main Building'],
            ['id' => 'RM-402', 'name' => 'Room 402', 'type' => 'Seminar Room', 'capacity' => 20, 'building' => 'Main Building'],
            ['id' => 'RM-501', 'name' => 'Room 501', 'type' => 'Conference Room', 'capacity' => 15, 'building' => 'Admin Building'],
        ];
        foreach ($rooms as $r) {
            Room::create($r);
        }

        // Laboratories
        $labs = [
            ['id' => 'LAB-001', 'name' => 'Computer Lab 1', 'capacity' => 30, 'building' => 'Main Building', 'equipment' => ['Computers', 'Projector', 'Whiteboard']],
            ['id' => 'LAB-002', 'name' => 'Computer Lab 2', 'capacity' => 30, 'building' => 'Main Building', 'equipment' => ['Computers', 'Projector', 'Whiteboard']],
            ['id' => 'LAB-003', 'name' => 'Network Lab', 'capacity' => 25, 'building' => 'Main Building', 'equipment' => ['Routers', 'Switches', 'Servers']],
            ['id' => 'LAB-004', 'name' => 'Multimedia Lab', 'capacity' => 20, 'building' => 'Main Building', 'equipment' => ['Computers', 'Graphics Tablets', 'Cameras']],
            ['id' => 'LAB-005', 'name' => 'Research Lab', 'capacity' => 15, 'building' => 'Main Building', 'equipment' => ['High-end Computers', 'VR Equipment', '3D Printer']],
        ];
        foreach ($labs as $l) {
            Laboratory::create($l);
        }

        // Events
        $events = [
            ['id' => 'EVT-001', 'title' => 'Tech Summit 2024', 'description' => 'Annual technology conference', 'date' => '2024-03-15', 'time' => '09:00:00', 'location' => 'Main Auditorium', 'category' => 'curricular', 'organizer' => 'CS Department'],
            ['id' => 'EVT-002', 'title' => 'Hackathon 2024', 'description' => '24-hour coding competition', 'date' => '2024-04-20', 'time' => '18:00:00', 'location' => 'Computer Lab 1', 'category' => 'extra-curricular', 'organizer' => 'IT Society'],
            ['id' => 'EVT-003', 'title' => 'Career Fair', 'description' => 'Interview opportunities with tech companies', 'date' => '2024-02-10', 'time' => '08:00:00', 'location' => 'Main Building Lobby', 'category' => 'curricular', 'organizer' => 'Career Services'],
            ['id' => 'EVT-004', 'title' => 'Workshop: Web Development', 'description' => 'Hands-on workshop on modern web frameworks', 'date' => '2024-03-25', 'time' => '13:00:00', 'location' => 'Computer Lab 2', 'category' => 'curricular', 'organizer' => 'CS Department'],
            ['id' => 'EVT-005', 'title' => 'Sports Festival', 'description' => 'Annual sports day activities', 'date' => '2024-05-01', 'time' => '07:00:00', 'location' => 'Sports Complex', 'category' => 'extra-curricular', 'organizer' => 'Student Council'],
            ['id' => 'EVT-006', 'title' => 'Research Symposium', 'description' => 'Student research presentations', 'date' => '2024-04-05', 'time' => '09:00:00', 'location' => 'Room 301', 'category' => 'curricular', 'organizer' => 'Research Office'],
            ['id' => 'EVT-007', 'title' => 'Music Concert', 'description' => 'Student band performance', 'date' => '2024-06-15', 'time' => '18:00:00', 'location' => 'Open Air Theater', 'category' => 'extra-curricular', 'organizer' => 'Music Club'],
            ['id' => 'EVT-008', 'title' => 'AI Exhibition', 'description' => 'Showcase of AI projects', 'date' => '2024-03-30', 'time' => '10:00:00', 'location' => 'Main Building Lobby', 'category' => 'curricular', 'organizer' => 'AI Club'],
            ['id' => 'EVT-009', 'title' => 'Volunteer Day', 'description' => 'Community outreach program', 'date' => '2024-05-20', 'time' => '07:00:00', 'location' => 'Off-campus', 'category' => 'extra-curricular', 'organizer' => 'Volunteer Club'],
            ['id' => 'EVT-010', 'title' => 'Midterm Examination', 'description' => 'Midterm exams for all courses', 'date' => '2024-03-11', 'time' => '08:00:00', 'location' => 'Various Rooms', 'category' => 'curricular', 'organizer' => 'Registrar Office'],
        ];
        foreach ($events as $e) {
            Event::create($e);
        }

        // Syllabi
        $syllabi = [
            ['id' => 'SYL-001', 'course_id' => 'CS101', 'semester' => '1st', 'academic_year' => '2023-2024', 'topics' => ['Introduction to Computers', 'Number Systems', 'Basic Programming Concepts'], 'requirements' => ['Attendance', 'Quizzes', 'Final Exam']],
            ['id' => 'SYL-002', 'course_id' => 'CS102', 'semester' => '1st', 'academic_year' => '2023-2024', 'topics' => ['Python Basics', 'Control Structures', 'Functions', 'Data Types'], 'requirements' => ['Labs', 'Projects', 'Final Exam']],
            ['id' => 'SYL-003', 'course_id' => 'CS201', 'semester' => '2nd', 'academic_year' => '2023-2024', 'topics' => ['OOP Concepts', 'Classes and Objects', 'Inheritance', 'Polymorphism'], 'requirements' => ['Labs', 'Midterm', 'Final Project']],
        ];
        foreach ($syllabi as $s) {
            Syllabus::create($s);
        }

        // Lessons
        $lessons = [
            ['id' => 'LES-001', 'course_id' => 'CS101', 'week' => 1, 'title' => 'Introduction to Computing', 'objectives' => ['Understand computer basics', 'Learn about hardware and software'], 'activities' => ['Lecture', 'Demonstration'], 'duration' => '2 hours'],
            ['id' => 'LES-002', 'course_id' => 'CS101', 'week' => 2, 'title' => 'Number Systems', 'objectives' => ['Binary and hexadecimal', 'Conversions'], 'activities' => ['Lecture', 'Practice Exercises'], 'duration' => '2 hours'],
            ['id' => 'LES-003', 'course_id' => 'CS102', 'week' => 1, 'title' => 'Python Basics', 'objectives' => ['Setup Python environment', 'Basic syntax'], 'activities' => ['Lecture', 'Hands-on'], 'duration' => '2 hours'],
        ];
        foreach ($lessons as $l) {
            Lesson::create($l);
        }

        // Curricula
        $curricula = [
            ['id' => 'CUR-001', 'year' => 1, 'semester' => '1st', 'courses' => ['CS101', 'IT101', 'ENG101', 'MATH101'], 'total_units' => 18],
            ['id' => 'CUR-002', 'year' => 1, 'semester' => '2nd', 'courses' => ['CS102', 'IT102', 'ENG102', 'MATH102'], 'total_units' => 18],
            ['id' => 'CUR-003', 'year' => 2, 'semester' => '1st', 'courses' => ['CS201', 'IT201', 'CS203', 'MATH201'], 'total_units' => 18],
            ['id' => 'CUR-004', 'year' => 2, 'semester' => '2nd', 'courses' => ['CS202', 'IT202', 'CS204', 'MATH202'], 'total_units' => 18],
            ['id' => 'CUR-005', 'year' => 3, 'semester' => '1st', 'courses' => ['CS301', 'CS302', 'CS303', 'IT301'], 'total_units' => 15],
            ['id' => 'CUR-006', 'year' => 3, 'semester' => '2nd', 'courses' => ['CS304', 'CS305', 'IT302', 'CS306'], 'total_units' => 15],
            ['id' => 'CUR-007', 'year' => 4, 'semester' => '1st', 'courses' => ['CS401', 'CS402', 'CS403', 'CS404'], 'total_units' => 15],
            ['id' => 'CUR-008', 'year' => 4, 'semester' => '2nd', 'courses' => ['CS405', 'CS406', 'CS407', 'CS408'], 'total_units' => 15],
        ];
        foreach ($curricula as $c) {
            Curriculum::create($c);
        }

        // Schedules
        $schedules = [
            ['id' => 'SCH-001', 'course_id' => 'CS101', 'section_id' => 'SEC-001', 'faculty_id' => 'FAC-001', 'room_id' => 'RM-101', 'day' => 'Monday', 'start_time' => '08:00:00', 'end_time' => '10:00:00'],
            ['id' => 'SCH-002', 'course_id' => 'CS102', 'section_id' => 'SEC-002', 'faculty_id' => 'FAC-002', 'room_id' => 'RM-102', 'day' => 'Monday', 'start_time' => '10:00:00', 'end_time' => '12:00:00'],
            ['id' => 'SCH-003', 'course_id' => 'CS201', 'section_id' => 'SEC-004', 'faculty_id' => 'FAC-003', 'room_id' => 'LAB-001', 'day' => 'Tuesday', 'start_time' => '08:00:00', 'end_time' => '11:00:00'],
            ['id' => 'SCH-004', 'course_id' => 'IT201', 'section_id' => 'SEC-005', 'faculty_id' => 'FAC-005', 'room_id' => 'LAB-003', 'day' => 'Tuesday', 'start_time' => '13:00:00', 'end_time' => '16:00:00'],
            ['id' => 'SCH-005', 'course_id' => 'CS301', 'section_id' => 'SEC-006', 'faculty_id' => 'FAC-004', 'room_id' => 'RM-201', 'day' => 'Wednesday', 'start_time' => '08:00:00', 'end_time' => '10:00:00'],
            ['id' => 'SCH-006', 'course_id' => 'CS302', 'section_id' => 'SEC-007', 'faculty_id' => 'FAC-006', 'room_id' => 'LAB-001', 'day' => 'Wednesday', 'start_time' => '10:00:00', 'end_time' => '13:00:00'],
            ['id' => 'SCH-007', 'course_id' => 'IT301', 'section_id' => 'SEC-006', 'faculty_id' => 'FAC-007', 'room_id' => 'LAB-002', 'day' => 'Thursday', 'start_time' => '08:00:00', 'end_time' => '11:00:00'],
            ['id' => 'SCH-008', 'course_id' => 'CS401', 'section_id' => 'SEC-008', 'faculty_id' => 'FAC-008', 'room_id' => 'RM-301', 'day' => 'Friday', 'start_time' => '08:00:00', 'end_time' => '10:00:00'],
        ];
        foreach ($schedules as $s) {
            Schedule::create($s);
        }

        // Faculty Assignments
        $assignments = [
            ['id' => 'FA-001', 'faculty_id' => 'FAC-001', 'course_id' => 'CS101', 'section_id' => 'SEC-001', 'academic_year' => '2023-2024', 'semester' => '1st'],
            ['id' => 'FA-002', 'faculty_id' => 'FAC-002', 'course_id' => 'CS102', 'section_id' => 'SEC-002', 'academic_year' => '2023-2024', 'semester' => '1st'],
            ['id' => 'FA-003', 'faculty_id' => 'FAC-003', 'course_id' => 'CS201', 'section_id' => 'SEC-004', 'academic_year' => '2023-2024', 'semester' => '1st'],
            ['id' => 'FA-004', 'faculty_id' => 'FAC-004', 'course_id' => 'CS301', 'section_id' => 'SEC-006', 'academic_year' => '2023-2024', 'semester' => '1st'],
            ['id' => 'FA-005', 'faculty_id' => 'FAC-005', 'course_id' => 'IT201', 'section_id' => 'SEC-005', 'academic_year' => '2023-2024', 'semester' => '1st'],
            ['id' => 'FA-006', 'faculty_id' => 'FAC-006', 'course_id' => 'CS302', 'section_id' => 'SEC-007', 'academic_year' => '2023-2024', 'semester' => '1st'],
            ['id' => 'FA-007', 'faculty_id' => 'FAC-007', 'course_id' => 'IT301', 'section_id' => 'SEC-006', 'academic_year' => '2023-2024', 'semester' => '1st'],
            ['id' => 'FA-008', 'faculty_id' => 'FAC-008', 'course_id' => 'CS401', 'section_id' => 'SEC-008', 'academic_year' => '2023-2024', 'semester' => '1st'],
        ];
        foreach ($assignments as $a) {
            FacultyAssignment::create($a);
        }

        // Student Academic Histories
        $academicHistories = [
            ['student_id' => 2600001, 'level' => 'elementary', 'school_name' => 'Mapua Elementary School', 'address' => 'Manila', 'year_graduated' => 2015, 'honors' => 'With Honors'],
            ['student_id' => 2600001, 'level' => 'high_school', 'school_name' => 'Mapua High School', 'address' => 'Manila', 'year_graduated' => 2021, 'honors' => 'Valedictorian'],
            ['student_id' => 2600002, 'level' => 'elementary', 'school_name' => 'San Jose Elementary School', 'address' => 'Quezon City', 'year_graduated' => 2016, 'honors' => null],
            ['student_id' => 2600002, 'level' => 'high_school', 'school_name' => 'San Jose National High School', 'address' => 'Quezon City', 'year_graduated' => 2022, 'honors' => 'With High Honors'],
            ['student_id' => 2600003, 'level' => 'elementary', 'school_name' => 'Ateneo de Manila Grade School', 'address' => 'Loyola Heights, Quezon City', 'year_graduated' => 2014, 'honors' => 'Salutatorian'],
            ['student_id' => 2600003, 'level' => 'high_school', 'school_name' => 'Ateneo de Manila High School', 'address' => 'Loyola Heights, Quezon City', 'year_graduated' => 2020, 'honors' => 'With Honors'],
            ['student_id' => 2600005, 'level' => 'elementary', 'school_name' => 'La Salle Green Hills', 'address' => 'Mandaluyong', 'year_graduated' => 2016, 'honors' => null],
            ['student_id' => 2600005, 'level' => 'high_school', 'school_name' => 'La Salle Green Hills High School', 'address' => 'Mandaluyong', 'year_graduated' => 2022, 'honors' => 'With Honors'],
            ['student_id' => 2600010, 'level' => 'elementary', 'school_name' => 'Pasig City Elementary School', 'address' => 'Pasig City', 'year_graduated' => 2015, 'honors' => 'With Honors'],
            ['student_id' => 2600010, 'level' => 'high_school', 'school_name' => 'Pasig City Science High School', 'address' => 'Pasig City', 'year_graduated' => 2021, 'honors' => 'Valedictorian'],
        ];
        foreach ($academicHistories as $h) {
            StudentAcademicHistory::create($h);
        }

        // Student Extra-Curriculars
        $extraCurriculars = [
            ['student_id' => 2600001, 'name' => 'Chess Club', 'role' => 'President', 'organization' => 'CCS Chess Club', 'start_year' => 2022, 'end_year' => null],
            ['student_id' => 2600001, 'name' => 'Hackathon Team', 'role' => 'Team Lead', 'organization' => 'IT Society', 'start_year' => 2023, 'end_year' => null],
            ['student_id' => 2600002, 'name' => 'Dance Troupe', 'role' => 'Member', 'organization' => 'CCS Cultural Arts', 'start_year' => 2022, 'end_year' => 2023],
            ['student_id' => 2600003, 'name' => 'Robotics Club', 'role' => 'Vice President', 'organization' => 'CCS Robotics', 'start_year' => 2021, 'end_year' => null],
            ['student_id' => 2600003, 'name' => 'Basketball Varsity', 'role' => 'Player', 'organization' => 'CCS Athletics', 'start_year' => 2020, 'end_year' => 2022],
            ['student_id' => 2600005, 'name' => 'Programming Club', 'role' => 'Secretary', 'organization' => 'Code Club', 'start_year' => 2022, 'end_year' => null],
            ['student_id' => 2600006, 'name' => 'Student Council', 'role' => 'Treasurer', 'organization' => 'CCS Student Government', 'start_year' => 2022, 'end_year' => 2023],
            ['student_id' => 2600010, 'name' => 'Math Olympiad', 'role' => 'Participant', 'organization' => 'Science & Math Society', 'start_year' => 2021, 'end_year' => 2022],
            ['student_id' => 2600014, 'name' => 'Photography Club', 'role' => 'Member', 'organization' => 'CCS Arts', 'start_year' => 2023, 'end_year' => null],
        ];
        foreach ($extraCurriculars as $ec) {
            StudentExtraCurricular::create($ec);
        }

        // Student Violations
        $violations = [
            ['student_id' => 2600009, 'description' => 'Excessive absences in CS302', 'date' => '2023-10-05', 'penalty' => 'Written Warning', 'status' => 'resolved', 'remarks' => 'Student acknowledged and improved attendance'],
            ['student_id' => 2600009, 'description' => 'Late submission of final project', 'date' => '2023-12-01', 'penalty' => '10% grade deduction', 'status' => 'resolved', 'remarks' => null],
            ['student_id' => 2600019, 'description' => 'Disruptive behavior during lecture', 'date' => '2023-09-15', 'penalty' => 'Verbal Warning', 'status' => 'resolved', 'remarks' => 'Counseling session completed'],
            ['student_id' => 2600007, 'description' => 'Academic dishonesty — copied lab report', 'date' => '2024-02-20', 'penalty' => 'Zero on the activity', 'status' => 'resolved', 'remarks' => 'Incident report filed'],
            ['student_id' => 2600013, 'description' => 'Unauthorized use of lab equipment after hours', 'date' => '2024-03-10', 'penalty' => 'Suspension from lab for 1 week', 'status' => 'pending', 'remarks' => null],
        ];
        foreach ($violations as $v) {
            StudentViolation::create($v);
        }

        // Student Skills
        $skills = [
            ['student_id' => 2600001, 'name' => 'Python', 'category' => 'Programming', 'proficiency' => 'advanced', 'description' => 'Data analysis and scripting'],
            ['student_id' => 2600001, 'name' => 'React', 'category' => 'Web Development', 'proficiency' => 'intermediate', 'description' => 'Frontend component development'],
            ['student_id' => 2600001, 'name' => 'Public Speaking', 'category' => 'Soft Skills', 'proficiency' => 'advanced', 'description' => null],
            ['student_id' => 2600003, 'name' => 'Java', 'category' => 'Programming', 'proficiency' => 'advanced', 'description' => 'Android and enterprise applications'],
            ['student_id' => 2600003, 'name' => 'Machine Learning', 'category' => 'Data Science', 'proficiency' => 'intermediate', 'description' => 'Familiar with scikit-learn and TensorFlow basics'],
            ['student_id' => 2600005, 'name' => 'JavaScript', 'category' => 'Programming', 'proficiency' => 'intermediate', 'description' => 'Vanilla JS and Node.js'],
            ['student_id' => 2600006, 'name' => 'UI/UX Design', 'category' => 'Design', 'proficiency' => 'intermediate', 'description' => 'Figma and Adobe XD'],
            ['student_id' => 2600006, 'name' => 'Leadership', 'category' => 'Soft Skills', 'proficiency' => 'advanced', 'description' => null],
            ['student_id' => 2600010, 'name' => 'SQL', 'category' => 'Database', 'proficiency' => 'advanced', 'description' => 'MySQL and PostgreSQL'],
            ['student_id' => 2600010, 'name' => 'PHP', 'category' => 'Programming', 'proficiency' => 'intermediate', 'description' => 'Laravel framework'],
            ['student_id' => 2600014, 'name' => 'C++', 'category' => 'Programming', 'proficiency' => 'beginner', 'description' => 'Basic algorithms and data structures'],
            ['student_id' => 2600015, 'name' => 'Cloud (AWS)', 'category' => 'DevOps', 'proficiency' => 'beginner', 'description' => 'EC2, S3 basics'],
        ];
        foreach ($skills as $s) {
            StudentSkill::create($s);
        }

        // Student Organizations
        $organizations = [
            ['student_id' => 2600001, 'organization_name' => 'Philippine Society of IT Students (PSITS)', 'position' => 'Chapter Representative', 'type' => 'Academic', 'start_year' => 2022, 'end_year' => null, 'is_active' => true],
            ['student_id' => 2600003, 'organization_name' => 'Junior Philippine Computer Society (JPCS)', 'position' => 'Vice President', 'type' => 'Academic', 'start_year' => 2021, 'end_year' => null, 'is_active' => true],
            ['student_id' => 2600003, 'organization_name' => 'IEEE Student Branch', 'position' => 'Member', 'type' => 'Professional', 'start_year' => 2022, 'end_year' => null, 'is_active' => true],
            ['student_id' => 2600006, 'organization_name' => 'CCS Student Council', 'position' => 'Treasurer', 'type' => 'Student Government', 'start_year' => 2022, 'end_year' => 2023, 'is_active' => false],
            ['student_id' => 2600006, 'organization_name' => 'Association of Computer Science Students', 'position' => 'Secretary', 'type' => 'Academic', 'start_year' => 2023, 'end_year' => null, 'is_active' => true],
            ['student_id' => 2600010, 'organization_name' => 'Philippine Society of IT Students (PSITS)', 'position' => 'President', 'type' => 'Academic', 'start_year' => 2023, 'end_year' => null, 'is_active' => true],
            ['student_id' => 2600015, 'organization_name' => 'Cloud Computing Club', 'position' => 'Member', 'type' => 'Technical', 'start_year' => 2023, 'end_year' => null, 'is_active' => true],
            ['student_id' => 2600017, 'organization_name' => 'Junior Philippine Computer Society (JPCS)', 'position' => 'Member', 'type' => 'Academic', 'start_year' => 2022, 'end_year' => null, 'is_active' => true],
        ];
        foreach ($organizations as $o) {
            StudentOrganization::create($o);
        }
    }
}