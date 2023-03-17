-- Add image field to posts table
ALTER TABLE posts ADD COLUMN image VARCHAR(255) NULL;

-- Create likes table
CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    post_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (user_id, post_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Create categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add category_id to posts table
ALTER TABLE posts ADD COLUMN category_id INT NULL;
ALTER TABLE posts ADD FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;

-- Add profile_image to users table
ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) NULL DEFAULT 'default.jpg';

-- Insert some default categories
INSERT INTO categories (name, slug) VALUES
('Technology', 'technology'),
('Lifestyle', 'lifestyle'),
('Travel', 'travel'),
('Food', 'food'),
('Health', 'health'); #  
