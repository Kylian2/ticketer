CREATE TABLE users (
    email VARCHAR(150) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(50) NOT NULL,
    role ENUM('admin', 'tester') DEFAULT 'tester',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('new', 'in_progress', 'closed') DEFAULT 'new',
    category ENUM('bug', 'upgrade', 'feedback', 'feature_request', 'support') DEFAULT 'bug',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user VARCHAR(150),
    FOREIGN KEY (user) REFERENCES users(email)
);

CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user VARCHAR(150),
    FOREIGN KEY (ticket) REFERENCES tickets(id),
    FOREIGN KEY (user) REFERENCES users(email)
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user VARCHAR(150),
    FOREIGN KEY (ticket) REFERENCES tickets(id),
    FOREIGN KEY (user) REFERENCES users(email)
);