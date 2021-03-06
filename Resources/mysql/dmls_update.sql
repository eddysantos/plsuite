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

-- CREATE TABLE `plsuite`.`omni_pos_log` (
-- 	`tran_id` int(18) NOT NULL,
-- 	`tran_ts` timestamp NOT NULL,
-- 	`driverid1` varchar(25),
-- 	`driverid2` varchar(25),
-- 	`tractor` varchar(10),
-- 	`lat` varchar(15),
-- 	`lon` varchar(15),
-- 	`speed` varchar(10),
-- 	PRIMARY KEY (`tran_id`)
-- ) ENGINE=`InnoDB` COMMENT='';


/***** DOCUMENT UPLOAD UPDATE... *****/

CREATE TABLE `plsuite`.`document_catalog` (
	`pkid_document` int NOT NULL,
	`document_type` varchar(255) NOT NULL,
	`document_name` varchar(255) NOT NULL,
	`document_url` varchar(1000) NOT NULL,
	`date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`added_by` varchar(255) NOT NULL,
	`fk_related_category` int NOT NULL,
	PRIMARY KEY (`pkid_document`, `document_type`)
) ENGINE=`InnoDB` COMMENT='';
