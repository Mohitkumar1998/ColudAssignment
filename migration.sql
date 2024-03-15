-- Drop table if exists
DROP TABLE IF EXISTS products;

-- Create table
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    amount NUMERIC NOT NULL,
    description TEXT
);

-- Insert dummy records
INSERT INTO products (name, amount, description) VALUES ('Dettol Hadwash', 10.50, 'Handwash');
INSERT INTO products (name, amount, description) VALUES ('Amul Cool', 20.75, 'Milk');
INSERT INTO products (name, amount, description) VALUES ('Dell Inspirion 5625', 15.00, 'Laptop');

