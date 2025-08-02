CREATE DATABASE recipe_db;
USE recipe_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    full_name VARCHAR(100)
);

CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    total_points INT DEFAULT 0,
    rate_count INT DEFAULT 0,
    score FLOAT DEFAULT 0
);

CREATE TABLE recipe_ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT,
    ingredient VARCHAR(100),
    quantity VARCHAR(50),
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);

CREATE TABLE recipe_steps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT,
    step_number INT,
    description TEXT,
    duration_minutes INT,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);

CREATE TABLE favourites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    recipe_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);

INSERT INTO recipes (title, category) VALUES
('Spaghetti Bolognese', 'Main'),
('Vegan Pancakes', 'Dessert'),
('Healthy Pizza', 'Main'),
('Easy Lamb Biryani', 'Main'),
('Couscous Salad', 'Salad'),
('Plum Clafoutis', 'Dessert'),
('Mango Pie', 'Dessert'),
('Mushroom Doner', 'Main');


INSERT INTO users (username, email, password, full_name) VALUES
('johndoe', 'john@example.com', 'password', 'John Doe'),
('janedoe', 'jane@example.com', 'password', 'Jane Doe');

INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES
(1, 'Spaghetti', '200g'), (1, 'Minced beef', '250g'), (1, 'Onion', '1'),
(1, 'Garlic', '2 cloves'), (1, 'Tomato', '400g'), (1, 'Herbs', 'To taste'),
(1, 'Olive oil', '2 tbsp'), (1, 'Salt', 'To taste'), (1, 'Pepper', 'To taste');

INSERT INTO recipe_steps (recipe_id, step_number, description, duration_minutes) VALUES
(1,1,'Boil spaghetti in salted water.',10),
(1,2,'Cook beef with onion and garlic.',10),
(1,3,'Add tomatoes and herbs.',5),
(1,4,'Simmer sauce.',15),
(1,5,'Mix with spaghetti and serve.',5);

INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES
(2, 'Flour', '120g'), (2, 'Almond milk', '250ml'), (2, 'Baking powder', '1 tsp'),
(2, 'Sugar', '2 tbsp'), (2, 'Salt', 'Pinch'), (2, 'Oil', '2 tbsp');

INSERT INTO recipe_steps (recipe_id, step_number, description, duration_minutes) VALUES
(2,1,'Mix dry ingredients.',2),
(2,2,'Add almond milk and whisk.',2),
(2,3,'Heat pan with oil.',2),
(2,4,'Pour batter and cook until bubbles form.',3),
(2,5,'Flip and cook the other side.',2);

INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES
(3, 'Wholewheat flour', '200g'), (3, 'Tomato sauce', '100g'), (3, 'Mozzarella', '100g'),
(3, 'Mixed veggies', '100g'), (3, 'Olive oil', '1 tbsp');

INSERT INTO recipe_steps (recipe_id, step_number, description, duration_minutes) VALUES
(3,1,'Prepare dough from flour and water.',15),
(3,2,'Roll out dough.',5),
(3,3,'Spread tomato sauce.',2),
(3,4,'Add cheese and veggies.',3),
(3,5,'Bake in oven.',20);

INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES
(4, 'Lamb', '250g'), (4, 'Basmati rice', '200g'), (4, 'Onion', '1'),
(4, 'Spices', 'To taste'), (4, 'Yogurt', '50g'), (4, 'Herbs', 'To taste');

INSERT INTO recipe_steps (recipe_id, step_number, description, duration_minutes) VALUES
(4,1,'Fry onions and spices.',5),
(4,2,'Add lamb and brown.',10),
(4,3,'Mix in yogurt and herbs.',3),
(4,4,'Layer rice over lamb.',2),
(4,5,'Cook until rice is done.',20);

INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES
(5, 'Couscous', '150g'), (5, 'Vegetables', '150g'), (5, 'Lemon juice', '2 tbsp'),
(5, 'Olive oil', '2 tbsp'), (5, 'Herbs', 'To taste');

INSERT INTO recipe_steps (recipe_id, step_number, description, duration_minutes) VALUES
(5,1,'Prepare couscous.',5),
(5,2,'Chop vegetables.',5),
(5,3,'Mix couscous with veggies.',2),
(5,4,'Add dressing.',2),
(5,5,'Toss and serve.',1);

INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES
(6, 'Plums', '300g'), (6, 'Flour', '100g'), (6, 'Sugar', '100g'),
(6, 'Eggs', '2'), (6, 'Milk', '200ml'), (6, 'Butter', '30g'),
(6, 'Vanilla extract', '1 tsp'), (6, 'Salt', 'Pinch');

INSERT INTO recipe_steps (recipe_id, step_number, description, duration_minutes) VALUES
(6,1,'Preheat oven.',5),
(6,2,'Slice and arrange plums in dish.',5),
(6,3,'Mix batter.',5),
(6,4,'Pour batter over plums.',2),
(6,5,'Bake until set.',30);

INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES
(7, 'Mangoes', '2'), (7, 'Pie crust', '1'), (7, 'Sugar', '80g'),
(7, 'Eggs', '2'), (7, 'Cream', '100ml'), (7, 'Cornstarch', '2 tbsp'),
(7, 'Lemon juice', '1 tbsp');

INSERT INTO recipe_steps (recipe_id, step_number, description, duration_minutes) VALUES
(7,1,'Prepare crust.',5),
(7,2,'Mix mango with sugar and cornstarch.',5),
(7,3,'Blend in eggs, cream, and lemon juice.',3),
(7,4,'Pour filling into crust.',2),
(7,5,'Bake, then chill.',30);

INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES
(8, 'Mushrooms', '200g'), (8, 'Flatbread', '2'), (8, 'Onion', '1'),
(8, 'Tomato', '1'), (8, 'Lettuce', '50g'), (8, 'Yogurt sauce', '50g'), (8, 'Spices', 'To taste');

INSERT INTO recipe_steps (recipe_id, step_number, description, duration_minutes) VALUES
(8,1,'Slice and sauté mushrooms with spices.',8),
(8,2,'Warm flatbread.',2),
(8,3,'Prepare veggies.',5),
(8,4,'Assemble flatbread with filling.',3),
(8,5,'Serve with yogurt sauce.',1);

INSERT INTO favourites (user_id, recipe_id) VALUES
(1, 3),
(2, 2);
