CREATE DATABASE IF NOT EXISTS hackathon_radar
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE hackathon_radar;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS hackathons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    organizer VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    deadline DATE NOT NULL,
    prize VARCHAR(150) NOT NULL DEFAULT '',
    registration_url VARCHAR(500) NOT NULL
);

CREATE TABLE IF NOT EXISTS competitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    organizer VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    deadline DATE NOT NULL,
    prize VARCHAR(150) NOT NULL DEFAULT '',
    registration_url VARCHAR(500) NOT NULL
);

CREATE TABLE IF NOT EXISTS saved_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    item_type ENUM('hackathon', 'competition') NOT NULL,
    UNIQUE KEY unique_saved_item (user_id, item_id, item_type),
    CONSTRAINT fk_saved_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO hackathons (title, organizer, description, deadline, prize, registration_url) VALUES
('Demo FutureTech Hackathon', 'Hackathon Radar Demo Team',
 'Demonstration entry: build a project using technology to solve a real-world problem.',
 '2027-03-15', 'Demonstration prize: $1,000', 'https://example.com/'),
('Demo Campus Code Sprint', 'Hackathon Radar Demo Team',
 'Demonstration entry: a beginner-friendly team coding event for students.',
 '2027-04-20', 'Demonstration prize: certificates', 'https://example.com/');

INSERT INTO competitions (title, organizer, description, deadline, prize, registration_url) VALUES
('Demo Student Innovation Challenge', 'Hackathon Radar Demo Team',
 'Demonstration entry: present an original idea and explain how it could help your community.',
 '2027-05-10', 'Demonstration prize: $500', 'https://example.com/'),
('Demo Web Design Contest', 'Hackathon Radar Demo Team',
 'Demonstration entry: design a clear and accessible website for a sample brief.',
 '2027-06-01', 'Demonstration prize: certificates', 'https://example.com/');
