CREATE DATABASE IF NOT EXISTS user_form_db_Tkach;
USE user_form_db_Tkach;

CREATE TABLE IF NOT EXISTS event_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(255) NOT NULL,
    number_of_people INT NOT NULL,
    menu_preferences TEXT,
    contact_phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

GRANT ALL PRIVILEGES ON user_form_db_Tkach.* TO 'user'@'%';
FLUSH PRIVILEGES;
