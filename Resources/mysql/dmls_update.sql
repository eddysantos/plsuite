-- ALTER TABLE `plsuite`.`ct_drivers` ADD COLUMN `pay_per_mile` decimal(5,2) NOT NULL DEFAULT 0.4 AFTER `default_truck`;
-- ALTER TABLE `plsuite`.`ct_truck`
--   ADD COLUMN `pay_per_mile` decimal(5,2) NOT NULL DEFAULT 1.05 AFTER `deletedTruck`,
--   ADD COLUMN `apply_surcharge` tinyint NOT NULL DEFAULT 0 AFTER `pay_per_mile`;
-- ALTER TABLE `plsuite`.`ct_truck` ADD COLUMN `create_settlement` tinyint AFTER `apply_surcharge`;
-- ALTER TABLE `plsuite`.`ct_trip_linehaul_movement` ADD COLUMN `mov_settled` tinyint AFTER `added_by`;


-- ALTER TABLE `plsuite`.`ct_trip` ADD COLUMN `trip_number_i` int AFTER `trip_number`;
-- ALTER TABLE `plsuite`.`ct_trip` ADD COLUMN `first_movement` varchar(15) AFTER `added_by`, ADD COLUMN `last_movement` varchar(15) AFTER `first_movement`;
--
-- /******** QUERY TO GET SETTLEMENT TRUCK LIST**********/
--
-- SELECT
--
-- t.truckNumber			truckNumber,
-- d.nameFirst			name_first,
-- d.nameLast 			name_last,
-- t.truckOwnedBy			truck_owner,
-- count(tlm.pkid_movement)	amt_movments,
-- count(distinct(tlm.fkid_linehaul))	amt_trips,
-- sum(tlm.miles_google)		miles
--
-- FROM
--
-- ct_truck t
--
-- LEFT JOIN ct_drivers d ON d.pkid_driver = t.truckOwnedBy AND d.isOwner = "Yes"
-- LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.fkid_tractor = t.pkid_truck
-- LEFT JOIN ct_trip_linehaul tl ON tlm.fkid_linehaul = tl.pk_idlinehaul
--
-- WHERE
--
-- 	t.create_settlement = 1
-- AND	t.truckStatus = "Active"
-- AND	tlm.mov_settled IS NULL
-- AND 	tl.linehaul_status = "Closed"
--
-- GROUP BY truckNumber
--
-- /***********************************************************




ALTER TABLE `plsuite`.`Users` ADD COLUMN `email` varchar(300) NOT NULL AFTER `Privileges`;
ALTER TABLE `plsuite`.`Users` ADD COLUMN `fkid_broker` varchar(5) AFTER `email`;
ALTER TABLE `plsuite`.`users_permisos` ADD COLUMN `administration_role` tinyint AFTER `invoice_control_save`, ADD COLUMN `user_management_role` tinyint AFTER `administration_role`, ADD COLUMN `user_management_role_add_users` tinyint AFTER `user_management_role`, ADD COLUMN `user_management_role_change_permisssions` tinyint AFTER `user_management_role_add_users`, ADD COLUMN `invoice_control_role` tinyint AFTER `user_management_role_change_permisssions`, CHANGE COLUMN `invoice_control_save` `invoice_control_save` tinyint AFTER `invoice_control_role`, ADD COLUMN `trip_role` tinyint AFTER `invoice_control_save`, ADD COLUMN `trip_role_editing` tinyint AFTER `trip_role`, ADD COLUMN `trip_role_reopen_closed_trips` tinyint AFTER `trip_role_editing`;
