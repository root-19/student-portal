<?php


    class SubjectManager {
        private $subjects = [
            '11' => [
                'ICT' => [
                    '1st semester' => [
                        'Comprog 1 & 2', 'Oral Communication', 'PE 1', 'PR 1', 'Komunikasyon at Pananaliksik',
                        'General Mathematics', 'Earth & Life Science', 'Empowerment Technology', '21st Century',
                    ],
                    '2nd semester' => [
                        'Comprog 3 & 4', 'PE 2', 'Reading and Writing', 'Pagbasa at Pagsusuri', 'Statistics and Probabilities',
                        'Personal Development', 'EAPP', 'Phy Sci', 'TechVoc',
                    ],
                ],
                'ABM' => [
                    '1st semester' => [
                        'Oral Com', 'Komunikasyon', 'Gen Math', 'Earth & Life', '21st Century', 'PE 1', 'PR 1',
                        'Empowerment Technology', 'Business Math', 'Organization & Management',
                    ],
                    '2nd semester' => [
                        'Reading & Writing', 'Pagbasa', 'Stats & Prob', 'PE 2', 'Personal Development', 'Physical Science',
                        'EAPP', 'Filipino sa Larang ng Akademik', 'FABM 1', 'Principles Of Marketing',
                    ],
                ],
                'HE' => [
                    '1st semester' => [
                        'Oral Communication', 'General Mathematics', 'Cookery 1 & 2', 'Empowerment Technology', 
                        'Practical Research 1', 'Komunikasyon', 'Earth and Life Science', 'PE 1', '21st Century',
                    ],
                    '2nd semester' => [
                        'Reading and Writing', 'Statistics and Probability', 'TechVoc', 'PE 2', 'Physical Science', 
                        'Cookery 3 & 4', 'Pagbasa', 'Personal Development', 'EAPP',
                    ],
                ],
            ],
            '12' => [
                'STEM' => [
                    '1st semester' => ['UCSP', 'Philosophy', 'PE 3', 'Physical Science', 'EAPP', 'FABM 2'],
                    '2nd semester' => ['Work Immersion', 'Applied Economics', 'Research in Daily Life', 'PE 4', 'Capstone Project'],
                ],
                'HUMMS' => [
                    '1st semester' => ['UCSP', 'Entrepreneurship', 'PE 3', 'Business Ethics', 'Business Finance', 'FABM 2'],
                    '2nd semester' => ['Research in Daily Life', 'EAPP', 'Introduction to World Religions', 'PE 4', 'Capstone Project'],
                ],
                'ABM' => [
                    '1st semester' => ['UCSP', 'CPAR', 'Philosophy', 'PE 3', 'PR 2', 'Entrepreneurship', 'FABM 2', 'Applied Economics'],
                    '2nd semester' => ['Business Ethics', 'Business Finance', 'MIL', 'Business Enterprise', 'I.I.I', 'PE 4'],
                ],
                'ICT' => [
                    '1st semester' => ['CPAR', 'Intro to Philo', 'CSS 1 & 2', 'Entrepreneurship', 'PE 3', 'PR 2', 'UCSP'],
                    '2nd semester' => ['MIL', 'PE 4', 'I.I.I', 'CHS 3 & 4', 'Work Immersion'],
                ],
                'HE' => [
                    '1st semester' => ['Entrepreneurship', 'Practical Research 2', 'PE 3', 'FBS', 'CPAR', 'UCSP', 'Intro to Philosophy'],
                    '2nd Semester' => ['Work Immersion', 'Capstone Project', 'MIL', 'EAPP', 'PE 4', 'Cookery'],
                ],
            ],
        ];
        public function getSubjects($grade, $strand, $semester) {
            $grade = (string) $grade; // Ensure grade is a string
            $strand = strtoupper(trim($strand)); // Convert strand to uppercase
            $semester = ucfirst(strtolower(trim($semester))); // Format semester properly
        
            if (isset($this->subjects[$grade][$strand][$semester])) {
                return $this->subjects[$grade][$strand][$semester];
            }
            return []; // Return empty array if not found
        }
        
        
    }
?>
  