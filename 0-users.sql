-- (A) USERS TABLE
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `name` (`name`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- (B) DUMMY USERS
INSERT INTO `users` (`id`, `name`, `email`) VALUES
(1, 'John Doe', 'john@doe.com'),
(2, 'Jane Doe', 'jane@doe.com'),
(3, 'Apple Doe', 'apple@doe.com'),
(4, 'Beck Doe', 'beck@doe.com'),
(5, 'Charlie Doe', 'charlie@doe.com'),
(6, 'Charles Doe', 'charles@doe.com'),
(7, 'Dion Doe', 'dion@doe.com'),
(8, 'Dee Doe', 'dee@doe.com'),
(9, 'Emily Doe', 'emily@doe.com'),
(10, 'Ethan Doe', 'ethan@doe.com'),
(11, 'Frank Doe', 'frank@doe.com'),
(12, 'Gina Doe', 'gina@doe.com'),
(13, 'Hela Doe', 'hela@doe.com'),
(14, 'Hubert Doe', 'hubert@doe.com'),
(15, 'Ivy Doe', 'ivy@doe.com'),
(16, 'Ingrid Doe', 'ingrid@doe.com'),
(17, 'James Doe', 'james@doe.com'),
(18, 'Jace Doe', 'jace@doe.com'),
(19, 'Kate Doe', 'kate@doe.com'),
(20, 'Luke Doe', 'luke@doe.com');
