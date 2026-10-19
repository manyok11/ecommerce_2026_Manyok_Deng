-- Fix: extend product_keywords column to VARCHAR(500)
-- Run if you get "Data too long for column 'product_keywords'" errors.
-- mysql -u root ecommerce_2026A_manyok_deng < database/fix_product_keywords.sql

ALTER TABLE `products`
    MODIFY COLUMN `product_keywords` VARCHAR(500) DEFAULT NULL;
