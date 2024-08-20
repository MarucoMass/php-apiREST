CREATE DATABASE IF NOT EXISTS booking_system;
USE booking_system;

CREATE TABLE IF NOT EXISTS classrooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    capacity_per_class INT NOT NULL
);

CREATE TABLE IF NOT EXISTS timetables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classroom_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id) 
);

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classroom_id INT NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    booked_by VARCHAR(255) NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id)
);

INSERT INTO classrooms (name, capacity_per_class) VALUES
('Math Classroom', 10),
('Art Classroom', 15),
('Science Classroom', 7),
('Geography Classroom', 15),
('Computer Science Classroom', 23),
('History Classroom', 11);

INSERT INTO timetables (classroom_id, day_of_week, start_time, end_time) VALUES
(1, 'Monday', '09:00:00', '11:00:00'),
(1, 'Monday', '11:00:00', '13:00:00'),
(1, 'Monday', '13:00:00', '15:00:00'),
(1, 'Monday', '15:00:00', '17:00:00'),
(1, 'Monday', '17:00:00', '19:00:00'),
(1, 'Tuesday', '09:00:00', '11:00:00'),
(1, 'Tuesday', '11:00:00', '13:00:00'),
(1, 'Tuesday', '13:00:00', '15:00:00'),
(1, 'Tuesday', '15:00:00', '17:00:00'),
(1, 'Tuesday', '17:00:00', '19:00:00'),
(1, 'Wednesday', '09:00:00', '11:00:00'),
(1, 'Wednesday', '11:00:00', '13:00:00'),
(1, 'Wednesday', '13:00:00', '15:00:00'),
(1, 'Wednesday', '15:00:00', '17:00:00'),
(1, 'Wednesday', '17:00:00', '19:00:00');


INSERT INTO timetables (classroom_id, day_of_week, start_time, end_time) VALUES
(2, 'Monday', '08:00:00', '09:00:00'),
(2, 'Monday', '09:00:00', '10:00:00'),
(2, 'Monday', '10:00:00', '11:00:00'),
(2, 'Monday', '11:00:00', '12:00:00'),
(2, 'Monday', '12:00:00', '13:00:00'),
(2, 'Monday', '13:00:00', '14:00:00'),
(2, 'Monday', '14:00:00', '15:00:00'),
(2, 'Monday', '15:00:00', '16:00:00'),
(2, 'Monday', '16:00:00', '17:00:00'),
(2, 'Monday', '17:00:00', '18:00:00'),
(2, 'Thursday', '08:00:00', '09:00:00'),
(2, 'Thursday', '09:00:00', '10:00:00'),
(2, 'Thursday', '10:00:00', '11:00:00'),
(2, 'Thursday', '11:00:00', '12:00:00'),
(2, 'Thursday', '12:00:00', '13:00:00'),
(2, 'Thursday', '13:00:00', '14:00:00'),
(2, 'Thursday', '14:00:00', '15:00:00'),
(2, 'Thursday', '15:00:00', '16:00:00'),
(2, 'Thursday', '16:00:00', '17:00:00'),
(2, 'Thursday', '17:00:00', '18:00:00'),
(2, 'Saturday', '08:00:00', '09:00:00'),
(2, 'Saturday', '09:00:00', '10:00:00'),
(2, 'Saturday', '10:00:00', '11:00:00'),
(2, 'Saturday', '11:00:00', '12:00:00'),
(2, 'Saturday', '12:00:00', '13:00:00'),
(2, 'Saturday', '13:00:00', '14:00:00'),
(2, 'Saturday', '14:00:00', '15:00:00'),
(2, 'Saturday', '15:00:00', '16:00:00'),
(2, 'Saturday', '16:00:00', '17:00:00'),
(2, 'Saturday', '17:00:00', '18:00:00');


INSERT INTO timetables (classroom_id, day_of_week, start_time, end_time) VALUES
(3, 'Tuesday', '15:00:00', '16:00:00'),
(3, 'Tuesday', '16:00:00', '17:00:00'),
(3, 'Tuesday', '17:00:00', '18:00:00'),
(3, 'Tuesday', '18:00:00', '19:00:00'),
(3, 'Tuesday', '19:00:00', '20:00:00'),
(3, 'Tuesday', '20:00:00', '21:00:00'),
(3, 'Tuesday', '21:00:00', '22:00:00'),
(3, 'Friday', '15:00:00', '16:00:00'),
(3, 'Friday', '16:00:00', '17:00:00'),
(3, 'Friday', '17:00:00', '18:00:00'),
(3, 'Friday', '18:00:00', '19:00:00'),
(3, 'Friday', '19:00:00', '20:00:00'),
(3, 'Friday', '20:00:00', '21:00:00'),
(3, 'Friday', '21:00:00', '22:00:00'),
(3, 'Saturday', '15:00:00', '16:00:00'),
(3, 'Saturday', '16:00:00', '17:00:00'),
(3, 'Saturday', '17:00:00', '18:00:00'),
(3, 'Saturday', '18:00:00', '19:00:00'),
(3, 'Saturday', '19:00:00', '20:00:00'),
(3, 'Saturday', '20:00:00', '21:00:00'),
(3, 'Saturday', '21:00:00', '22:00:00');


INSERT INTO timetables (classroom_id, day_of_week, start_time, end_time) VALUES
(4, 'Thursday', '08:00:00', '10:00:00'),
(4, 'Thursday', '10:00:00', '12:00:00'),
(4, 'Thursday', '12:00:00', '14:00:00'),
(4, 'Thursday', '14:00:00', '16:00:00'),
(4, 'Thursday', '16:00:00', '18:00:00'),
(4, 'Friday', '08:00:00', '10:00:00'),
(4, 'Friday', '10:00:00', '12:00:00'),
(4, 'Friday', '12:00:00', '14:00:00'),
(4, 'Friday', '14:00:00', '16:00:00'),
(4, 'Friday', '16:00:00', '18:00:00');


INSERT INTO timetables (classroom_id, day_of_week, start_time, end_time) VALUES
(5, 'Monday', '13:00:00', '14:00:00'),
(5, 'Monday', '14:00:00', '15:00:00'),
(5, 'Friday', '13:00:00', '14:00:00'),
(5, 'Friday', '14:00:00', '15:00:00');


INSERT INTO timetables (classroom_id, day_of_week, start_time, end_time) VALUES
(6, 'Tuesday', '10:00:00', '13:00:00'),
(6, 'Tuesday', '13:00:00', '16:00:00'),
(6, 'Tuesday', '16:00:00', '19:00:00'),
(6, 'Wednesday', '10:00:00', '13:00:00'),
(6, 'Wednesday', '13:00:00', '16:00:00'),
(6, 'Wednesday', '16:00:00', '19:00:00');



