-- =============================================================
-- Migration: add profile_image column to customer table
-- Run once:  mysql -u root shoppn < database/add_profile_image.sql
-- Or paste into phpMyAdmin's SQL tab.
-- =============================================================

ALTER TABLE `customer`
    ADD COLUMN `customer_image` VARCHAR(255) NULL DEFAULT NULL
    COMMENT 'Stored filename (not the original upload name) in uploads/avatars/';
