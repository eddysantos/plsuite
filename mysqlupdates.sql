ALTER TABLE `plsuite`.`ct_trip_linehaul_movement`
ADD COLUMN `origin_location_name` varchar(255) NULL AFTER `fkid_linehaul`,
ADD COLUMN `origin_location_street` varchar(255) NULL AFTER `origin_location_name`,
ADD COLUMN `origin_location_street_number` varchar(255) NULL AFTER `origin_location_street`,
ADD COLUMN `origin_country` varchar(255) NULL AFTER `origin_zip`,
ADD COLUMN `destination_location_name` varchar(255) NULL AFTER `origin_country`,
ADD COLUMN `destination_location_street` varchar(255) NULL AFTER `destination_location_name`,
ADD COLUMN `destination_location_street_number` varchar(255) NULL AFTER `destination_location_street`,
ADD COLUMN `destination_country` varchar(255) NULL AFTER `destination_zip`;
ADD COLUMN `appointment_from` datetime NULL AFTER `mov_settled`,
ADD COLUMN `appointment_to` datetime NULL AFTER `appointment_from`;
ADD COLUMN `origin_formatted_address` varchar(500) NULL AFTER `fkid_linehaul`,
ADD COLUMN `destination_formatted_address` varchar(500) NULL AFTER `origin_country`;
