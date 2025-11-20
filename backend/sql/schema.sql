-- SQL schema for a basic Tinder-like backend (MySQL syntax)

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `people` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `age` INT NOT NULL,
  `location` VARCHAR(255),
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pictures` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `person_id` BIGINT UNSIGNED NOT NULL,
  `url` TEXT NOT NULL,
  `order` INT DEFAULT 0,
  FOREIGN KEY (`person_id`) REFERENCES `people`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Track likes from a user to a person (you can also use "swipes" table for more verb detail)
CREATE TABLE `likes` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `person_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('like','dislike') NOT NULL DEFAULT 'like',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`person_id`) REFERENCES `people`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Helpful index to count likes quickly
CREATE INDEX idx_person_likes ON likes(person_id, type);
