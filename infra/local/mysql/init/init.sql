SELECT DATABASE();
SHOW TABLES;

DROP TABLE IF EXISTS duty_history;
DROP TABLE IF EXISTS duties;
DROP TABLE IF EXISTS users;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(255) DEFAULT NULL,
    modified DATETIME DEFAULT NULL,
    created DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_name_idx` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DESCRIBE users;

CREATE TABLE IF NOT EXISTS duties (
     id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
     user_id BIGINT UNSIGNED NOT NULL,
     user_contact VARCHAR(255) NOT NULL,
     started DATETIME NOT NULL,
     ended DATETIME NOT NULL,
     created DATETIME NOT NULL,
     comment VARCHAR(255) DEFAULT NULL,
     PRIMARY KEY (`id`),
     KEY `duties_user_id_idx` (`user_id`),
     KEY `duties_period_idx` (`started`, `ended`),
     CONSTRAINT `duties_users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DESCRIBE duties;

CREATE TABLE IF NOT EXISTS duty_history (
      id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
      duty_id BIGINT UNSIGNED NOT NULL,
      user_id BIGINT UNSIGNED NOT NULL,
      act ENUM('set', 'unset') NOT NULL,
      created DATETIME NOT NULL,
      duty_data TEXT NOT NULL,
      PRIMARY KEY (`id`),
      KEY `duties_duty_id_idx` (`duty_id`),
      KEY `duties_user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DESCRIBE duty_history;
