-- (A) ORDERS TABLE --
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `order_name` varchar(255) NOT NULL,
  `order_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `name` (`order_name`),
  ADD KEY `email` (`order_email`),
  ADD KEY `order_date` (`order_date`);

ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

-- (B) ORDERS ITEMS TABLE --
CREATE TABLE `orders_items` (
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `orders_items`
  ADD PRIMARY KEY (`order_id`,`book_id`);

-- (C) PRODUCTS TABLE --
CREATE TABLE `products` (
  `book_id` int(11) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `book_author` varchar(255) NOT NULL,
  `pages` int(11) NOT NULL,
  `book_Format` varchar(255) NOT NULL,
  `book_coverImg` varchar(255) DEFAULT NULL,
  `book_description` text DEFAULT NULL,
  `book_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `publisher` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `products`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `name` (`book_title`);

ALTER TABLE `products`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT;