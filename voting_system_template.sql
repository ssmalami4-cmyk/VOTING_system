
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL, -- Store hashed passwords
  `email` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `candidates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `office` VARCHAR(255) NOT NULL,
  `faculty` VARCHAR(255) NOT NULL,
  `department` VARCHAR(255) NOT NULL,
  `level` VARCHAR(255) NOT NULL,
  `photo` VARCHAR(255) NOT NULL,
  `approved` TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `voters` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `student_id` VARCHAR(255) NOT NULL UNIQUE,
  `faculty` VARCHAR(255) NOT NULL,
  `department` VARCHAR(255) NOT NULL,
  `level` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `votes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `voter_id` INT NOT NULL,
  `candidate_id` INT NOT NULL,
  FOREIGN KEY (`voter_id`) REFERENCES `voters`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`candidate_id`) REFERENCES `candidates`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `admins` (`username`, `password`, `email`) VALUES
('admin', '$2y$10$NiB2f1BUji6pRhpqXpRzkOdhDgIv9KrfOqazQLHrLs6BUSoZeMeTi', 'admin@vote.com'); 

CREATE TABLE IF NOT EXISTS `results` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `office` VARCHAR(255) NOT NULL,
    `candidate_name` VARCHAR(255) NOT NULL,
    `votes` INT NOT NULL,
    `winner` TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `elections` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `start_date` DATETIME NOT NULL,
    `end_date` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `candidates`
ADD COLUMN `election_id` INT NOT NULL,
ADD FOREIGN KEY (`election_id`) REFERENCES `elections`(`id`);


ALTER TABLE `results`
ADD COLUMN `election_id` INT NOT NULL,
ADD FOREIGN KEY (`election_id`) REFERENCES `elections`(`id`);


ALTER TABLE `voters`
ADD COLUMN `election_id` INT NOT NULL,
ADD FOREIGN KEY (`election_id`) REFERENCES `elections`(`id`);

ALTER TABLE voters ADD COLUMN election_id INT;

CREATE TABLE IF NOT EXISTS `offices` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
