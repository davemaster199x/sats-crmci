-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: satscom_dblive
-- ------------------------------------------------------
-- Server version	5.7.44-log-cll-lve

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accomodation`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accomodation` (
  `accomodation_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` float(5,2) NOT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` mediumtext COLLATE utf8mb4_unicode_ci,
  `lng` mediumtext COLLATE utf8mb4_unicode_ci,
  `street_number` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suburb` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` int(11) DEFAULT NULL,
  `assigned_region` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`accomodation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=570 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_01_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_01_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_01_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_01_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_01_2024`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_01_2024` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_02_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_02_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_02_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_02_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_03_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_03_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_03_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_03_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_04_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_04_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_04_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_04_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_05_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_05_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_05_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_05_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_06_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_06_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_06_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_06_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_07_2021`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_07_2021` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_07_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_07_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_07_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_07_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_08_2021`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_08_2021` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_08_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_08_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_08_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_08_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_09_2021`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_09_2021` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_09_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_09_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_09_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_09_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_10_2021`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_10_2021` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_10_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_10_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_10_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_10_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_11_2021`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_11_2021` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_11_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_11_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_11_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_11_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_12_2021`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_12_2021` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_12_2022`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_12_2022` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_12_2023`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_12_2023` (
  `property_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `active_properties_cron`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_properties_cron` (
  `cronid` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `crontitle` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `crontable` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `crondate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cronstatus` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cronid`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_doc_header`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_doc_header` (
  `admin_doc_header_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`admin_doc_header_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_documents`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_documents` (
  `admin_documents_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_doc_header_id` int(11) DEFAULT NULL,
  `filename` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `url` mediumtext COLLATE utf8mb4_unicode_ci,
  `type` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`admin_documents_id`)
) ENGINE=InnoDB AUTO_INCREMENT=353 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agencies_from_other_company`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agencies_from_other_company` (
  `afoc_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `added_date` date DEFAULT NULL,
  PRIMARY KEY (`afoc_id`),
  KEY `agency_id_idx` (`agency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=912 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency` (
  `agency_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `agency_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_1` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_2` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_3` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tot_properties` smallint(4) NOT NULL,
  `letter1` tinyint(1) NOT NULL DEFAULT '0',
  `letter2` tinyint(1) NOT NULL DEFAULT '0',
  `letter3` tinyint(1) NOT NULL DEFAULT '0',
  `noletter` tinyint(1) NOT NULL DEFAULT '0',
  `mailing_as_above` tinyint(1) DEFAULT '1',
  `mailing_address` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_id` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','deactivated','target') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `account_emails` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accounts_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accounts_phone` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_emails` tinyint(1) DEFAULT '0',
  `agency_emails` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `p_tmp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `p_converted` tinyint(4) DEFAULT '1',
  `custom_alarm_pricing` tinyint(1) DEFAULT '0',
  `send_combined_invoice` tinyint(1) DEFAULT '1',
  `agency_region_id` int(10) NOT NULL,
  `salesrep` int(10) DEFAULT '0',
  `send_entry_notice` tinyint(1) DEFAULT '1',
  `alt_agency_id` int(10) DEFAULT NULL,
  `tmh_imported` timestamp NULL DEFAULT NULL,
  `contact_first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pass_timestamp` datetime NOT NULL,
  `require_work_order` int(11) NOT NULL,
  `allow_indiv_pm` int(11) NOT NULL,
  `tot_prop_timestamp` datetime NOT NULL,
  `franchise_groups_id` int(11) NOT NULL,
  `agency_using_id` int(11) NOT NULL,
  `legal_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci,
  `auto_renew` int(11) NOT NULL DEFAULT '1',
  `key_allowed` int(11) DEFAULT NULL,
  `key_email_req` int(11) DEFAULT NULL,
  `agency_hours` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` mediumtext COLLATE utf8mb4_unicode_ci,
  `lng` mediumtext COLLATE utf8mb4_unicode_ci,
  `postcode_region_id` int(11) DEFAULT NULL,
  `phone_call_req` int(11) DEFAULT NULL,
  `abn` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allow_dk` int(11) NOT NULL DEFAULT '1',
  `website` mediumtext COLLATE utf8mb4_unicode_ci,
  `allow_en` int(11) NOT NULL DEFAULT '-1',
  `agency_specific_notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `team_meeting` mediumtext COLLATE utf8mb4_unicode_ci,
  `new_job_email_to_agent` int(11) NOT NULL DEFAULT '1',
  `save_notes` int(11) DEFAULT NULL,
  `escalate_notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `escalate_notes_ts` datetime DEFAULT NULL,
  `tenant_details_contact_name` mediumtext COLLATE utf8mb4_unicode_ci,
  `tenant_details_contact_phone` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_bpay` tinyint(4) DEFAULT '1' COMMENT 'default to YES',
  `agency_special_deal` mediumtext COLLATE utf8mb4_unicode_ci,
  `trust_account_software` int(11) DEFAULT NULL,
  `allow_indiv_pm_email_cc` int(11) NOT NULL DEFAULT '0',
  `allow_upfront_billing` int(11) NOT NULL DEFAULT '0',
  `joined_sats` date DEFAULT NULL,
  `invoice_pm_only` int(11) DEFAULT NULL,
  `pt_completed` int(11) DEFAULT NULL,
  `pt_no_statement_needed` int(11) DEFAULT NULL,
  `pt_sent_to_va` int(11) DEFAULT NULL,
  `tas_connected` int(11) NOT NULL DEFAULT '0',
  `propertyme_agency_id` mediumtext COLLATE utf8mb4_unicode_ci,
  `send_statement_email_ts` datetime DEFAULT NULL,
  `electrician_only` int(11) NOT NULL DEFAULT '0',
  `initial_setup_done` int(11) NOT NULL,
  `esclate_notes_last_updated_by` int(11) DEFAULT NULL,
  `subscription_notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `subscription_notes_update_ts` datetime DEFAULT NULL,
  `subscription_notes_update_by` int(11) DEFAULT NULL,
  `multi_owner_discount` decimal(15,2) DEFAULT NULL,
  `deactivated_ts` date DEFAULT NULL,
  `deactivated_reason` mediumtext COLLATE utf8mb4_unicode_ci,
  `active_prop_with_sats` int(11) DEFAULT NULL,
  `send_en_to_agency` tinyint(4) NOT NULL DEFAULT '1',
  `en_to_pm` tinyint(4) NOT NULL DEFAULT '0',
  `load_api` tinyint(4) NOT NULL DEFAULT '1',
  `statements_agency_comments` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statements_agency_comments_ts` datetime DEFAULT NULL,
  `pme_supplier_id` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `accounts_reports` tinyint(4) NOT NULL DEFAULT '0',
  `palace_supplier_id` mediumtext COLLATE utf8mb4_unicode_ci,
  `palace_agent_id` mediumtext COLLATE utf8mb4_unicode_ci,
  `palace_diary_id` mediumtext COLLATE utf8mb4_unicode_ci,
  `api_billable` tinyint(4) NOT NULL DEFAULT '1',
  `no_bulk_match` tinyint(4) NOT NULL DEFAULT '0',
  `exclude_free_invoices` tinyint(4) NOT NULL,
  `send_48_hr_key` tinyint(4) NOT NULL,
  `add_inv_to_agen` tinyint(4) NOT NULL DEFAULT '0',
  `high_touch` tinyint(4) NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `deleted_timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`agency_id`),
  KEY `agency_name_idx` (`agency_name`(191)),
  KEY `postcode_idx` (`postcode`),
  KEY `address_idx` (`address_1`,`address_2`,`address_3`),
  KEY `ix_country_id` (`country_id`),
  KEY `ix_status_country` (`status`,`country_id`),
  KEY `idx_deleted_agency` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=10683 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_addresses`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) NOT NULL,
  `address_1` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_2` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_3` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `lat` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lng` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_alarms`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_alarms` (
  `agency_alarm_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) NOT NULL,
  `alarm_pwr_id` int(11) NOT NULL,
  `price` float(5,2) NOT NULL,
  PRIMARY KEY (`agency_alarm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73950 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_api`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_api` (
  `agency_api_id` int(11) NOT NULL AUTO_INCREMENT,
  `api_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`agency_api_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_api_data_capture`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_api_data_capture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `api_endpoint` text COLLATE utf8mb4_unicode_ci,
  `http_header` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci,
  `http_status_code` int(11) DEFAULT NULL,
  `raw_response` text COLLATE utf8mb4_unicode_ci,
  `other_errors` text COLLATE utf8mb4_unicode_ci,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54506 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_api_documents`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_api_documents` (
  `agency_api_documents_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) NOT NULL,
  `is_invoice` tinyint(1) DEFAULT '0',
  `is_certificate` tinyint(1) DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`agency_api_documents_id`),
  KEY `agency_api_documents_idx` (`agency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_api_integration`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_api_integration` (
  `api_integration_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `connected_service` int(11) DEFAULT NULL COMMENT 'API',
  `api_agency_id` mediumtext COLLATE utf8mb4_unicode_ci,
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `date_activated` date DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`api_integration_id`)
) ENGINE=InnoDB AUTO_INCREMENT=916 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_api_login`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_api_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `login_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_api_logs`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_api_logs` (
  `id` int(21) NOT NULL AUTO_INCREMENT,
  `agency_api_id` int(11) NOT NULL,
  `job_id` int(10) NOT NULL,
  `api_url` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_response` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=125921 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_api_request_count`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_api_request_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_id` int(11) DEFAULT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=224 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_api_tokens`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_api_tokens` (
  `agency_api_token_id` int(11) NOT NULL AUTO_INCREMENT,
  `api_id` int(11) DEFAULT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `access_token` mediumtext COLLATE utf8mb4_unicode_ci,
  `expiry` datetime DEFAULT NULL,
  `refresh_token` mediumtext COLLATE utf8mb4_unicode_ci,
  `created` datetime DEFAULT NULL,
  `connection_date` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `system_use` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`agency_api_token_id`),
  KEY `agency_id_idx` (`agency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=455 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_audits`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_audits` (
  `agency_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `submitted_by` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `comments` mediumtext COLLATE utf8mb4_unicode_ci,
  `completion_date` date DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `date_created` date DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`agency_audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_completed_increase`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_completed_increase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `submitted_by` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `agency_completed` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=919 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_default_service_price`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_default_service_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_type` int(11) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_event_log`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_event_log` (
  `agency_event_log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int(10) unsigned DEFAULT NULL,
  `comments` mediumtext COLLATE utf8mb4_unicode_ci,
  `eventdate` date DEFAULT NULL,
  `follow_up_on` date DEFAULT NULL,
  `contact_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agency_id` int(10) unsigned NOT NULL,
  `tmh_imported` timestamp NULL DEFAULT NULL,
  `staff_id` int(11) NOT NULL,
  `next_contact` date NOT NULL,
  `important` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `hide_delete` int(11) DEFAULT NULL,
  PRIMARY KEY (`agency_event_log_id`),
  KEY `agency_id_idx` (`agency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=79192 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_keys`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_keys` (
  `agency_keys_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `is_keys_picked_up` tinyint(4) DEFAULT NULL,
  `attend_property` tinyint(4) DEFAULT NULL,
  `job_reason` int(11) DEFAULT NULL COMMENT 'job not completed reason',
  `reason_comment` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_keys_returned` tinyint(4) DEFAULT NULL,
  `not_returned_notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `drop_off_ts` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`agency_keys_id`)
) ENGINE=InnoDB AUTO_INCREMENT=137347 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_leaving_reason`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_leaving_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `reason` int(11) DEFAULT NULL,
  `other_reason` text COLLATE utf8mb4_unicode_ci,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=553 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_maintenance`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_maintenance` (
  `agency_maintenance_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `maintenance_id` int(11) DEFAULT NULL,
  `price` float(5,2) DEFAULT NULL,
  `surcharge` int(11) DEFAULT NULL,
  `display_surcharge` int(11) DEFAULT NULL,
  `surcharge_msg` mediumtext COLLATE utf8mb4_unicode_ci,
  `updated_date` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`agency_maintenance_id`),
  KEY `agency_id_idx` (`agency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8199 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_marker_id_definition`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_marker_id_definition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marker_definition` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `yes` text COLLATE utf8mb4_unicode_ci,
  `no` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_markers`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `marker_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_onboarding`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_onboarding` (
  `onboarding_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` mediumtext COLLATE utf8mb4_unicode_ci,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`onboarding_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_onboarding_selected`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_onboarding_selected` (
  `onboarding_selected_id` int(11) NOT NULL AUTO_INCREMENT,
  `onboarding_id` int(11) DEFAULT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`onboarding_selected_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1953 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_other_pref`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_other_pref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `renewal_interval` int(11) DEFAULT NULL COMMENT 'in days',
  `renewal_start_offset` int(11) DEFAULT NULL COMMENT 'in days',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_payments`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_payments` (
  `agency_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reference` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_deposit` tinyint(4) NOT NULL,
  `remittance` tinyint(4) NOT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `allocated` decimal(15,2) NOT NULL,
  `remaining` decimal(15,2) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`agency_payments_id`)
) ENGINE=InnoDB AUTO_INCREMENT=635 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_payments_agencies`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_payments_agencies` (
  `agency_payments_agencies_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_payments_id` int(11) DEFAULT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`agency_payments_agencies_id`)
) ENGINE=InnoDB AUTO_INCREMENT=690 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_payments_jobs`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_payments_jobs` (
  `agency_payments_jobs_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_payments_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`agency_payments_jobs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=645 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_preference`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_preference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pref_text` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `yes_txt` mediumtext COLLATE utf8mb4_unicode_ci,
  `no_txt` mediumtext COLLATE utf8mb4_unicode_ci,
  `sort_index` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_preference_selected`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_preference_selected` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `agency_pref_id` int(11) DEFAULT NULL,
  `sel_pref_val` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_agency_preference_selected` (`agency_id`,`agency_pref_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4747 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_price_variation`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_price_variation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1 = discount, 2 = surcharge',
  `amount` decimal(12,2) NOT NULL,
  `reason` int(11) DEFAULT NULL,
  `scope` int(11) DEFAULT NULL COMMENT '0 -> agency, 1 -> property, >=2 -> service types',
  `expiry` date DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted_ts` datetime DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2015 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_price_variation_reason`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_price_variation_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_discount` tinyint(4) NOT NULL DEFAULT '1',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_priority`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_priority` (
  `priority_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deactivated_date` datetime DEFAULT NULL,
  `deactivated_by` int(11) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`priority_id`),
  KEY `agency_id_idx` (`agency_id`),
  KEY `idx_agency_priority` (`priority`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_priority_marker_definition`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_priority_marker_definition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `priority` int(11) NOT NULL DEFAULT '0',
  `abbreviation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority_full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agency_priority_idx` (`priority`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_regions`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_regions` (
  `agency_region_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) DEFAULT NULL,
  `agency_region_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agency_region_postcodes` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`agency_region_id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_services`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_services` (
  `agency_services_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `price` double(5,2) NOT NULL,
  PRIMARY KEY (`agency_services_id`),
  KEY `agency_id_idx` (`agency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15884 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_site_maintenance_mode`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_site_maintenance_mode` (
  `agency_site_maintenance_mode_id` int(11) NOT NULL AUTO_INCREMENT,
  `mode` int(11) NOT NULL,
  PRIMARY KEY (`agency_site_maintenance_mode_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_specific_brochures`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_specific_brochures` (
  `agency_specific_brochures_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(225) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(225) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agency_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`agency_specific_brochures_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_specific_service_price`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_specific_service_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_type` int(11) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_tracking`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_tracking` (
  `agency_tracking_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` mediumtext COLLATE utf8mb4_unicode_ci,
  `agency_id` int(11) DEFAULT NULL,
  `logged_in_datetime` datetime DEFAULT NULL,
  `logged_out_datetime` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `active` int(11) NOT NULL,
  `deleted` int(11) NOT NULL,
  PRIMARY KEY (`agency_tracking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_user_2fa`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_user_2fa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1 = mobile, 2 = email',
  `send_to` text COLLATE utf8mb4_unicode_ci,
  `code` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_sent_ts` datetime DEFAULT NULL,
  `offer_2fa_date` date DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2352 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_user_account_types`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_user_account_types` (
  `agency_user_account_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_index` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`agency_user_account_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_user_accounts`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_user_accounts` (
  `agency_user_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fname` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lname` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` mediumtext COLLATE utf8mb4_unicode_ci,
  `agency_id` int(11) DEFAULT NULL,
  `user_type` int(11) DEFAULT NULL,
  `phone` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_title` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` mediumtext COLLATE utf8mb4_unicode_ci,
  `reset_password_code` mediumtext COLLATE utf8mb4_unicode_ci,
  `reset_password_code_ts` datetime DEFAULT NULL,
  `password_changed_ts` datetime DEFAULT NULL,
  `hide_welcome_msg` tinyint(4) NOT NULL,
  `active` tinyint(4) DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  `alt_agencies` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`agency_user_account_id`),
  KEY `email_idx` (`email`(191)),
  KEY `agency_id_idx` (`agency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6733 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_user_logins`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_user_logins` (
  `agency_user_login_id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `ip` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`agency_user_login_id`)
) ENGINE=InnoDB AUTO_INCREMENT=349120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_using`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_using` (
  `agency_using_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`agency_using_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `airtable`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `airtable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `booked` tinyint(4) NOT NULL,
  `completed` tinyint(4) NOT NULL,
  `missed` tinyint(4) NOT NULL,
  `on_hold` tinyint(4) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `airtable_idx` (`job_id`,`booked`,`completed`,`missed`,`on_hold`)
) ENGINE=InnoDB AUTO_INCREMENT=16027 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alarm`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarm` (
  `alarm_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `alarm_power_id` int(11) NOT NULL,
  `alarm_type_id` int(11) NOT NULL,
  `new` tinyint(1) DEFAULT '0',
  `pass` tinyint(1) NOT NULL DEFAULT '0',
  `alarm_price` float(5,2) NOT NULL DEFAULT '0.00',
  `alarm_reason_id` int(11) NOT NULL,
  `make` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_fixing` tinyint(1) DEFAULT NULL,
  `ts_cleaned` tinyint(1) DEFAULT NULL,
  `ts_newbattery` tinyint(1) DEFAULT NULL,
  `ts_testbutton` tinyint(1) DEFAULT NULL,
  `ts_visualind` tinyint(1) DEFAULT NULL,
  `ts_simsmoke` tinyint(1) DEFAULT NULL,
  `ts_checkeddb` tinyint(1) DEFAULT NULL,
  `ts_meetsas1851` tinyint(1) DEFAULT NULL,
  `ts_expiry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_added` tinyint(1) DEFAULT NULL,
  `ts_discarded` tinyint(1) DEFAULT '0',
  `ts_discarded_reason` tinyint(1) DEFAULT '0',
  `ts_comments` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_trip_rate` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_item_number` int(11) DEFAULT '1',
  `alarm_job_type_id` int(11) DEFAULT '1',
  `ts_height` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_opening` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_pass_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_required_compliance` tinyint(1) DEFAULT '0',
  `tmh_alarm_id` int(11) DEFAULT NULL,
  `tmh_imported` timestamp NULL DEFAULT NULL,
  `ts_db_rating` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `window_type_cw` int(11) NOT NULL,
  `window_material_cw` int(11) NOT NULL,
  `blind_type_cw` int(11) NOT NULL,
  `ftlgt1_6m_cw` int(11) NOT NULL,
  `tag_present_cw` int(11) NOT NULL,
  `clip_rfc_cw` int(11) NOT NULL,
  `clip_present_cw` int(11) NOT NULL,
  `loop_lt220m_cw` int(11) NOT NULL,
  `od_gt1m_cw` int(11) NOT NULL,
  `nm_tested_cw` int(11) NOT NULL,
  `ts_is_alarm_ic` int(11) DEFAULT NULL,
  `ts_alarm_sounds_other` int(11) DEFAULT NULL,
  `rec_batt_exp` date DEFAULT NULL COMMENT 'Recording Battery Expiry',
  PRIMARY KEY (`alarm_id`),
  KEY `job_id_idx` (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3159581 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alarm_discarded_reason`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarm_discarded_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alarm_images`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarm_images` (
  `alarm_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_id` int(11) DEFAULT NULL,
  `location_image_filename` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry_image_filename` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_lat` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'image latitude, captured when image taken',
  `image_lng` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'image longitude, captured when image taken',
  `created` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`alarm_image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=109115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alarm_job_type`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarm_job_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `html_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `include_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bundle` int(11) NOT NULL,
  `bundle_ids` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sync_marker` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `full_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excluded_bundle_ids` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_ic` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alarm_price`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarm_price` (
  `price_id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_pwr_id` int(4) NOT NULL,
  `agency_id` int(4) NOT NULL DEFAULT '0',
  `alarm_price` float(5,2) NOT NULL DEFAULT '0.00',
  `alarm_job_type_id` int(11) DEFAULT '1',
  PRIMARY KEY (`price_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alarm_pwr`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarm_pwr` (
  `alarm_pwr_id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_pwr` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alarm_price_ex` float(5,2) NOT NULL DEFAULT '0.00',
  `alarm_price_inc` float(5,2) NOT NULL DEFAULT '0.00',
  `alarm_job_type_id` int(11) DEFAULT '1',
  `alarm_make` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alarm_model` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alarm_expiry` int(11) DEFAULT NULL,
  `alarm_type` int(11) NOT NULL DEFAULT '2',
  `active` int(11) NOT NULL DEFAULT '1',
  `is_240v` int(11) NOT NULL,
  `battery_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_replaceable` tinyint(1) DEFAULT NULL,
  `alarm_pwr_source` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_li` tinyint(4) NOT NULL,
  PRIMARY KEY (`alarm_pwr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alarm_reason`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarm_reason` (
  `alarm_reason_id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_reason` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alarm_job_type_id` int(11) DEFAULT '1',
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`alarm_reason_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alarm_type`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarm_type` (
  `alarm_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alarm_job_type_id` int(11) DEFAULT '1',
  PRIMARY KEY (`alarm_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `api_job_data`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_job_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crm_job_id` int(11) DEFAULT NULL,
  `api` int(11) DEFAULT NULL,
  `api_inv_uploaded` tinyint(4) NOT NULL,
  `api_cert_uploaded` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_job_id_idx` (`crm_job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=621 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `api_last_tenant_update`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_last_tenant_update` (
  `altu_id` int(11) NOT NULL AUTO_INCREMENT,
  `api_property_data_id` int(11) DEFAULT NULL,
  `last_updated_ts` datetime DEFAULT NULL,
  `checked_date` date DEFAULT NULL,
  `tenant_compared_date` date DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`altu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `api_property_data`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_property_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crm_prop_id` int(11) DEFAULT NULL,
  `api` int(11) DEFAULT NULL,
  `api_prop_id` mediumtext COLLATE utf8mb4_unicode_ci,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `crm_prop_id_idx` (`crm_prop_id`)
) ENGINE=InnoDB AUTO_INCREMENT=242394 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `api_tenancy_data`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_tenancy_data` (
  `atd_id` int(11) NOT NULL AUTO_INCREMENT,
  `api_property_data_id` int(11) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `agreement_start` date DEFAULT NULL,
  `agreement_end` date DEFAULT NULL,
  `checked_date` date DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`atd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `banners`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `banners_id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`banners_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `blind_type_cw`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blind_type_cw` (
  `blind_type_cw_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`blind_type_cw_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `booking_goals_logs`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_goals_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_run_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21082 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `booking_notes`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_notes` (
  `booking_notes_id` int(11) NOT NULL AUTO_INCREMENT,
  `notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `agency_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`booking_notes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `booking_notes_log`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_notes_log` (
  `booking_notes_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_notes_id` int(11) DEFAULT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `msg` mediumtext COLLATE utf8mb4_unicode_ci,
  `staff_id` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`booking_notes_log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bundle_services`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bundle_services` (
  `bundle_services_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `alarm_job_type_id` int(11) NOT NULL,
  `sync` int(11) NOT NULL,
  `completed` int(11) NOT NULL,
  PRIMARY KEY (`bundle_services_id`),
  KEY `job_id_idx` (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=601734 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cal_filters`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cal_filters` (
  `StaffId` int(11) NOT NULL,
  `StaffFilter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_class_filter` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`StaffId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `calendar`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar` (
  `calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_finish` date DEFAULT NULL,
  `region` longtext COLLATE utf8mb4_unicode_ci,
  `details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `tmh_imported` timestamp NULL DEFAULT NULL,
  `tmh_processed` tinyint(1) DEFAULT '0',
  `booking_target` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accomodation` int(11) DEFAULT NULL,
  `accomodation_id` int(11) DEFAULT NULL,
  `marked_as_leave` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `date_start_time` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_finish_time` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_staff` int(11) DEFAULT NULL COMMENT 'staff accounts',
  PRIMARY KEY (`calendar_id`),
  KEY `date_idx` (`date_start`,`date_finish`),
  KEY `ix_staff_id` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=101378 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `call_centre_data`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `call_centre_data` (
  `call_centre_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `shift_from` int(11) DEFAULT NULL,
  `shift_to` int(11) DEFAULT NULL,
  `check_in` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_call` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_call` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `7-8_am` int(11) DEFAULT NULL,
  `8-9_am` int(11) DEFAULT NULL,
  `9-10_am` int(11) DEFAULT NULL,
  `10-11_am` int(11) DEFAULT NULL,
  `11-12_pm` int(11) DEFAULT NULL,
  `12-1_pm` int(11) DEFAULT NULL,
  `1-2_pm` int(11) DEFAULT NULL,
  `2-3_pm` int(11) DEFAULT NULL,
  `3-4_pm` int(11) DEFAULT NULL,
  `4-5_pm` int(11) DEFAULT NULL,
  `5-6_pm` int(11) DEFAULT NULL,
  `6-7_pm` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `deleted` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`call_centre_data_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5986 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `colour_table`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colour_table` (
  `colour_table_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_run_id` int(11) DEFAULT NULL,
  `colour_id` int(11) DEFAULT NULL,
  `time` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jobs_num` int(11) DEFAULT NULL,
  `no_keys` int(11) DEFAULT NULL,
  `booking_status` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`colour_table_id`),
  KEY `tech_run_id_idx` (`tech_run_id`)
) ENGINE=InnoDB AUTO_INCREMENT=131804 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `complaints`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complaints` (
  `comp_id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_topic` int(11) DEFAULT NULL,
  `ticket_priority` int(11) DEFAULT NULL,
  `issue_summary` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_link` mediumtext COLLATE utf8mb4_unicode_ci,
  `describe_issue` mediumtext COLLATE utf8mb4_unicode_ci,
  `response` mediumtext COLLATE utf8mb4_unicode_ci,
  `requested_by` int(11) DEFAULT NULL,
  `last_updated_by` int(11) DEFAULT NULL,
  `last_updated_ts` datetime DEFAULT NULL,
  `screenshot` mediumtext COLLATE utf8mb4_unicode_ci,
  `status` int(11) DEFAULT '1',
  `completed_ts` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`comp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `complaints_agency`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complaints_agency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` int(10) NOT NULL,
  `agency_id` int(10) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `complaints_agency_temp`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complaints_agency_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `agency_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `complaints_assign_to`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complaints_assign_to` (
  `comp_assign_id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` int(10) NOT NULL,
  `staff_id` int(10) NOT NULL,
  PRIMARY KEY (`comp_assign_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `complaints_assign_to_temp`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complaints_assign_to_temp` (
  `comp_assign_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT 'Session staff_id',
  `staff_id` int(5) NOT NULL COMMENT 'Assign to ID',
  PRIMARY KEY (`comp_assign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `complaints_log`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complaints_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` int(11) DEFAULT NULL,
  `log_text` text COLLATE utf8mb4_unicode_ci,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `complaints_status`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complaints_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hex` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `index_sort` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `complaints_topic`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complaints_topic` (
  `comp_topic_id` int(100) NOT NULL AUTO_INCREMENT,
  `comp_topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` int(5) NOT NULL,
  PRIMARY KEY (`comp_topic_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `console_api_keys`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_key` mediumtext COLLATE utf8mb4_unicode_ci,
  `office_id` text COLLATE utf8mb4_unicode_ci COMMENT 'console agency ID',
  `agency_id` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `console_properties`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `console_prop_id` int(11) DEFAULT NULL,
  `crm_prop_id` int(11) DEFAULT NULL,
  `office_id` text COLLATE utf8mb4_unicode_ci COMMENT 'console agency ID',
  `full_address` mediumtext COLLATE utf8mb4_unicode_ci,
  `unit_num` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street_num` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suburb` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `hidden` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1123 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `console_property_compliance`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_property_compliance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `console_prop_id` int(11) DEFAULT NULL,
  `service_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `compliance_notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `expiry_date` date DEFAULT NULL,
  `last_inspection` date DEFAULT NULL,
  `qld_2022_comp` tinyint(4) NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `prop_comp_proc_id` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'property compliance process ID',
  `prop_comp_id` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'property compliance ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1713 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `console_property_other_info`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_property_other_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `console_prop_id` int(11) DEFAULT NULL,
  `key_number` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_details` mediumtext COLLATE utf8mb4_unicode_ci,
  `property_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_use` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `console_property_tenant_emails`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_property_tenant_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) DEFAULT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10022 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `console_property_tenant_phones`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_property_tenant_phones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) DEFAULT NULL,
  `number` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11233 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `console_property_tenants`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_property_tenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) DEFAULT NULL,
  `is_landlord` tinyint(4) NOT NULL,
  `console_prop_id` int(11) DEFAULT NULL,
  `console_tenancy_id` int(11) DEFAULT NULL,
  `first_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name_updated_ts` datetime DEFAULT NULL,
  `last_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name_updated_ts` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `new_tenants_ts` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3402 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `console_tenant_agreements`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_tenant_agreements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `console_prop_id` int(11) DEFAULT NULL,
  `lease_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inaugural_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `vacating_date` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3386 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `console_users`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `console_prop_id` int(11) DEFAULT NULL,
  `first_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2962 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `console_webhooks_data`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_webhooks_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `console_prop_id` int(11) DEFAULT NULL,
  `event_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'from console API json',
  `event_id` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'from console API json	',
  `json` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'from console API json	',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated_date_time` datetime DEFAULT NULL COMMENT 'from console API json',
  `office_id` int(11) DEFAULT NULL COMMENT 'from console API json',
  `actioned_by` int(11) DEFAULT NULL,
  `actioned_ts` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6555 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contacts`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `contact_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_home` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_mob` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_work` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_3` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landlord_id` int(10) unsigned zerofill DEFAULT NULL,
  `agency_id` int(10) unsigned DEFAULT NULL,
  `property_id` int(10) unsigned DEFAULT NULL,
  `alt_contact_id` int(11) DEFAULT NULL,
  `tmh_imported` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contractor_appointment`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contractor_appointment` (
  `contractor_appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` mediumtext COLLATE utf8mb4_unicode_ci,
  `file_path` mediumtext COLLATE utf8mb4_unicode_ci,
  `agency_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contractor_appointment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contractors`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contractors` (
  `contractors_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` float(5,2) NOT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`contractors_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `corded_window`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `corded_window` (
  `corded_window_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `covering` int(11) NOT NULL COMMENT 'blind_type_cw',
  `ftllt1_6m` int(11) NOT NULL,
  `tag_present` int(11) NOT NULL,
  `clip_rfc` int(11) NOT NULL,
  `clip_present` int(11) NOT NULL,
  `loop_lt220m` int(11) NOT NULL,
  `seventy_n` int(11) NOT NULL,
  `cw_image` mediumtext COLLATE utf8mb4_unicode_ci,
  `location` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_of_windows` int(11) DEFAULT NULL,
  PRIMARY KEY (`corded_window_id`),
  KEY `corded_window_idx` (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=283606 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `countries`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_percent` int(11) DEFAULT NULL,
  `states` int(11) DEFAULT NULL,
  `phone_prefix` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_number` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_number` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_signature` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letterhead_footer` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trading_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `outgoing_email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bsb` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abn` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ac_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ac_number` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `country_access`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country_access` (
  `country_access_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_accounts_id` int(11) NOT NULL,
  `country_id` tinyint(4) NOT NULL,
  `default` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`country_access_id`),
  KEY `ix_country_access_staff_accounts_id_country_id` (`staff_accounts_id`,`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4838 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `credit_reason`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_reason` (
  `credit_reason_id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`credit_reason_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `credit_request_adj_res`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_request_adj_res` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `credit_requests`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_requests` (
  `credit_request_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `date_of_request` datetime DEFAULT NULL,
  `requested_by` int(11) DEFAULT NULL,
  `reason` mediumtext COLLATE utf8mb4_unicode_ci,
  `date_processed` datetime DEFAULT NULL,
  `result` int(11) DEFAULT NULL,
  `reason_for_decline` mediumtext COLLATE utf8mb4_unicode_ci,
  `who` int(11) DEFAULT NULL,
  `comments` mediumtext COLLATE utf8mb4_unicode_ci,
  `amount_credited` decimal(10,2) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `deleted` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `adjustment_val_req` decimal(15,2) DEFAULT NULL,
  `invoice_paid` int(11) DEFAULT NULL,
  `refund_request` int(11) DEFAULT NULL,
  `refund_bank_details` mediumtext COLLATE utf8mb4_unicode_ci,
  `proof_of_payment_pdf` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_of_allocation_pdf` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_trail_pdf` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_pdf` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adjustment_type` int(11) DEFAULT NULL,
  `reason_for_adjustment` int(11) DEFAULT NULL COMMENT 'dropdown',
  PRIMARY KEY (`credit_request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13588 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_page_permission_class`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_page_permission_class` (
  `cppc_id` int(11) NOT NULL AUTO_INCREMENT,
  `page` int(11) DEFAULT NULL,
  `staff_class` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cppc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1922 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_page_permission_user`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_page_permission_user` (
  `cppu_id` int(11) NOT NULL AUTO_INCREMENT,
  `page` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `denied` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cppu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_pages`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_pages` (
  `crm_page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_url` mediumtext COLLATE utf8mb4_unicode_ci,
  `menu` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`crm_page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_settings`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_settings` (
  `crm_settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `auto_emails` int(11) NOT NULL,
  `cron_send_letters` int(11) DEFAULT NULL,
  `cron_merged_cert` int(11) DEFAULT NULL,
  `cron_merge_sms` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `agency_portal_vip_agencies` mediumtext COLLATE utf8mb4_unicode_ci,
  `sms_credit` int(11) NOT NULL,
  `sms_credit_update_ts` datetime DEFAULT NULL,
  `statements_generic_note` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statements_generic_note_ts` datetime DEFAULT NULL,
  `cron_pme_upload` int(11) DEFAULT NULL,
  `cron_mark_unservice` int(11) DEFAULT NULL,
  `disable_all_crons` tinyint(4) NOT NULL,
  `agency_portal_mm` tinyint(4) NOT NULL,
  PRIMARY KEY (`crm_settings_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_task_category`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_task_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_task_details_devs`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_task_details_devs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT NULL,
  `dev_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_task_details_sub_users`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_task_details_sub_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_task_managers`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_task_managers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_task_status`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_task_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hex` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `index_sort` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_task_sub_category`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_task_sub_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_category_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_tasks`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_tasks` (
  `crm_task_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) DEFAULT NULL,
  `sub_category` int(11) DEFAULT NULL,
  `ticket_priority` int(11) DEFAULT NULL,
  `issue_summary` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_link` mediumtext COLLATE utf8mb4_unicode_ci,
  `describe_issue` mediumtext COLLATE utf8mb4_unicode_ci,
  `response` mediumtext COLLATE utf8mb4_unicode_ci,
  `requested_by` int(11) DEFAULT NULL,
  `last_updated_by` int(11) DEFAULT NULL,
  `last_updated_ts` datetime DEFAULT NULL,
  `screenshot` mediumtext COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '1',
  `completed_ts` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`crm_task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1476 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_tasks_log`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_tasks_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT NULL,
  `log_text` text COLLATE utf8mb4_unicode_ci,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4728 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crm_user_logins`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_user_logins` (
  `crm_user_login_id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `ip` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_version` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` decimal(11,7) NOT NULL,
  `lng` decimal(11,7) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`crm_user_login_id`)
) ENGINE=InnoDB AUTO_INCREMENT=744810 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cron_log`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cron_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `week_no` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `started` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `finished` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `country_id` int(11) DEFAULT NULL,
  `triggered_by` int(11) DEFAULT NULL COMMENT 'session staff ID or use -1 for cron	',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49765 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cron_types`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cron_types` (
  `cron_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `cron_file` mediumtext COLLATE utf8mb4_unicode_ci,
  `ci_link` mediumtext COLLATE utf8mb4_unicode_ci,
  `active_cron` tinyint(4) NOT NULL DEFAULT '1',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cron_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `daily_figures`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_figures` (
  `daily_figure_id` int(11) NOT NULL AUTO_INCREMENT,
  `month` date DEFAULT NULL,
  `budget` double DEFAULT NULL,
  `working_days` double DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`daily_figure_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `daily_figures_per_date`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_figures_per_date` (
  `daily_figure_per_date_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `working_day` int(11) DEFAULT NULL,
  `techs` int(11) DEFAULT NULL,
  `jobs` int(11) DEFAULT NULL,
  `jobs_exc_ub_os` int(11) DEFAULT NULL,
  `sales` double DEFAULT NULL,
  `sales_ub_os_only` int(11) DEFAULT NULL,
  `sales_exc_ic_up` int(11) DEFAULT NULL,
  `sales_ic_up_only` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`daily_figure_per_date_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1957 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `device_accounts`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `account_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_identifier` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'username/email',
  `account_password` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=277 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `display_on`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `display_on` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `display_variation`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `display_variation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `variation_id` int(11) NOT NULL COMMENT 'agency_price_variation.id | job_variation.id',
  `type` int(11) NOT NULL COMMENT '1=agency, 2=job',
  `display_on` int(11) NOT NULL COMMENT 'display_on.id ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2518 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_templates`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates` (
  `email_templates_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temp_type` int(11) DEFAULT NULL,
  `body` mediumtext COLLATE utf8mb4_unicode_ci,
  `show_to_call_centre` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`email_templates_id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_templates_sent`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates_sent` (
  `email_templates_sent_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_log_id` int(11) DEFAULT NULL,
  `from_email` mediumtext COLLATE utf8mb4_unicode_ci,
  `to_email` mediumtext COLLATE utf8mb4_unicode_ci,
  `cc_email` mediumtext COLLATE utf8mb4_unicode_ci,
  `subject` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_body` mediumtext COLLATE utf8mb4_unicode_ci,
  `date_created` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `log_id` int(11) DEFAULT NULL COMMENT 'new log ID',
  `email_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`email_templates_sent_id`),
  KEY `job_log_id_idx` (`job_log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=264009 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_templates_tag`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates_tag` (
  `email_templates_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`email_templates_tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_templates_type`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates_type` (
  `email_templates_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`email_templates_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `escalate_agency_info`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escalate_agency_info` (
  `escalate_agency_info_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `left_message` int(11) DEFAULT NULL,
  `completed` int(11) DEFAULT NULL,
  `emailed` int(11) DEFAULT NULL,
  `notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `notes_timestamp` timestamp NULL DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`escalate_agency_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35725 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `escalate_job_reasons`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escalate_job_reasons` (
  `escalate_job_reasons_id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` mediumtext COLLATE utf8mb4_unicode_ci,
  `reason_short` mediumtext COLLATE utf8mb4_unicode_ci,
  `icon` mediumtext COLLATE utf8mb4_unicode_ci,
  `date_created` datetime DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '1',
  `country_id` int(11) NOT NULL,
  `sort_num` int(11) DEFAULT NULL,
  PRIMARY KEY (`escalate_job_reasons_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expense_account`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_account` (
  `expense_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`expense_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expense_summary`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_summary` (
  `expense_summary_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `employee` int(11) DEFAULT NULL,
  `total_amount` decimal(20,2) DEFAULT NULL,
  `date_reimbursed` date DEFAULT NULL,
  `who` int(11) DEFAULT NULL,
  `line_manager` int(11) DEFAULT NULL,
  `exp_sum_status` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`expense_summary_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2209 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expenses`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `card` int(11) NOT NULL DEFAULT '1',
  `supplier` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `account` int(11) DEFAULT NULL,
  `amount` decimal(20,2) DEFAULT NULL,
  `receipt_image` mediumtext COLLATE utf8mb4_unicode_ci,
  `file_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expense_summary_id` int(11) DEFAULT NULL,
  `entered_by` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`expense_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9570 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `extra_job_notes`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extra_job_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `not_compliant_notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=766 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `figures`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `figures` (
  `figures_id` int(11) NOT NULL AUTO_INCREMENT,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `working_days` int(11) DEFAULT NULL,
  `ym` int(11) DEFAULT NULL,
  `of` int(11) DEFAULT NULL,
  `cot` int(11) DEFAULT NULL,
  `lr` int(11) DEFAULT NULL,
  `fr` int(11) DEFAULT NULL,
  `upgrades` int(11) DEFAULT NULL,
  `upgrades_income` decimal(15,2) DEFAULT NULL,
  `jobs_not_comp` int(11) DEFAULT NULL,
  `new_sales` int(11) DEFAULT NULL,
  `renewals` int(11) DEFAULT NULL,
  `budget` int(11) DEFAULT NULL,
  `actual` int(11) DEFAULT NULL,
  `prev_year` int(11) DEFAULT NULL,
  `techs` int(11) DEFAULT NULL,
  `p_actual` int(11) DEFAULT NULL,
  `p_last_month` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `active` int(11) NOT NULL,
  `deleted` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `annual` int(11) DEFAULT NULL,
  `upfronts` int(11) DEFAULT NULL,
  `240v_rebook` int(11) DEFAULT NULL,
  `lost` int(11) DEFAULT NULL,
  PRIMARY KEY (`figures_id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `franchise_groups`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `franchise_groups` (
  `franchise_groups_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`franchise_groups_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `free_alarms`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `free_alarms` (
  `fa_id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_pwr_id` int(11) NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `free` int(11) DEFAULT '0',
  PRIMARY KEY (`fa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `free_alarms_display`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `free_alarms_display` (
  `fa_id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_pwr_id` int(11) NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `free` int(11) DEFAULT '0',
  PRIMARY KEY (`fa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `global_settings`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_settings` (
  `global_settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `allocate_personnel` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allocate_personnel_updated_by` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`global_settings_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hidden_api_property`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hidden_api_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_prop_id` mediumtext COLLATE utf8mb4_unicode_ci,
  `agency_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29360 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hidden_from_pages`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hidden_from_pages` (
  `hidden_from_pages_id` int(11) NOT NULL AUTO_INCREMENT,
  `hidden_from_pages_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`hidden_from_pages_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hidden_properties`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hidden_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  `hidden_from_pages` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `home_content_block`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `home_content_block` (
  `content_block_id` int(11) NOT NULL AUTO_INCREMENT,
  `content_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  PRIMARY KEY (`content_block_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `home_content_block_class_access`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `home_content_block_class_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_class` int(11) DEFAULT NULL,
  `content_block_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6480 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `home_content_block_users_block`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `home_content_block_users_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `content_block_id` int(11) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '999999',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37683 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icons`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icons` (
  `icon_id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `date_created` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`icon_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `incident_and_injury`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `incident_and_injury` (
  `incident_and_injury_id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime_of_incident` datetime DEFAULT NULL,
  `nature_of_incident` int(11) DEFAULT NULL,
  `location_of_incident` mediumtext COLLATE utf8mb4_unicode_ci,
  `describe_incident` mediumtext COLLATE utf8mb4_unicode_ci,
  `ip_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` mediumtext COLLATE utf8mb4_unicode_ci,
  `ip_occupation` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_dob` date DEFAULT NULL,
  `ip_tel_num` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_employer` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_noi` mediumtext COLLATE utf8mb4_unicode_ci,
  `ip_loi` mediumtext COLLATE utf8mb4_unicode_ci,
  `ip_onsite_treatment` int(11) DEFAULT NULL,
  `ip_further_treatment` int(11) DEFAULT NULL,
  `witness_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `witness_contact` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loss_time_injury` int(11) DEFAULT NULL,
  `reported_to` int(11) DEFAULT NULL,
  `confirm_chk` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `country_id` int(11) DEFAULT NULL,
  `department` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `were_the_police_notified` int(11) DEFAULT NULL,
  `reported_to_phone_number` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `further_treatment_details` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `injury_type_other_details` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`incident_and_injury_id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `incident_photos`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `incident_photos` (
  `incident_photos_id` int(11) NOT NULL AUTO_INCREMENT,
  `incident_and_injury_id` int(11) DEFAULT NULL,
  `image_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`incident_photos_id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `intentionally_hidden_active_properties`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intentionally_hidden_active_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_property_id` (`property_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice_credits`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_credits` (
  `invoice_credit_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `credit_date` date DEFAULT NULL,
  `credit_paid` decimal(12,2) DEFAULT NULL,
  `credit_reason` mediumtext COLLATE utf8mb4_unicode_ci,
  `approved_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` int(11) DEFAULT '1',
  `payment_reference` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`invoice_credit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16949 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice_payments`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_payments` (
  `invoice_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount_paid` decimal(12,2) DEFAULT NULL,
  `type_of_payment` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` int(11) DEFAULT '1',
  `payment_reference` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agen_pay_j_id` int(11) DEFAULT NULL COMMENT 'agency_payments_jobs_id',
  PRIMARY KEY (`invoice_payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20416 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice_refunds`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_refunds` (
  `invoice_refund_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount_paid` decimal(12,2) DEFAULT NULL,
  `type_of_payment` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `payment_reference` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`invoice_refund_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4860 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_compliance`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_compliance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `retest_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100795 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_jobtype`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_jobtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `alarm_job_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_log`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` smallint(5) unsigned DEFAULT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `eventdate` date DEFAULT NULL,
  `eventtime` char(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_type` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_id` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `tmh_imported` timestamp NULL DEFAULT NULL,
  `important` tinyint(1) DEFAULT NULL,
  `log_agency_id` int(11) DEFAULT NULL,
  `auto_process` tinyint(1) DEFAULT NULL,
  `log_type` tinyint(4) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `job_id_idx` (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10304905 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_markers`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `job_type_change` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_platform_invoice_note`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_platform_invoice_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_reason`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_reason` (
  `job_reason_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_message` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`job_reason_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_sync_log`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_sync_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL DEFAULT '0',
  `staff_id` int(11) NOT NULL DEFAULT '0',
  `unique_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 inprocess | 1: completed',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=442716 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_type`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_type` (
  `job_type` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abbrv` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`job_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_type_change`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_type_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_vacant_dates`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_vacant_dates` (
  `job_vacant_dates_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`job_vacant_dates_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_variation`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_variation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1 = discount, 2 = surcharge',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `date_applied` date DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2050 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date` date DEFAULT NULL,
  `status` enum('Action Required','Allocate','Booked','Cancelled','Completed','DHA','Escalate','Merged Certificates','On Hold','On Hold - COVID','Pending','Pre Completion','Send Letters','To Be Booked','To Be Invoiced') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tech_id` int(10) unsigned DEFAULT '1',
  `comments` text COLLATE utf8mb4_unicode_ci,
  `retest_interval` smallint(5) unsigned NOT NULL DEFAULT '365',
  `auto_renew` tinyint(1) NOT NULL DEFAULT '0',
  `job_type` enum('240v Rebook','Annual Visit','Change of Tenancy','Fix or Replace','IC Upgrade','Lease Renewal','Once-off','Yearly Maintenance') COLLATE utf8mb4_unicode_ci NOT NULL,
  `property_id` int(10) unsigned NOT NULL,
  `property_jobs_count` tinyint(4) NOT NULL DEFAULT '0',
  `time_of_day` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letter_sent` tinyint(1) NOT NULL DEFAULT '0',
  `tech_comments` text COLLATE utf8mb4_unicode_ci,
  `sort_order` smallint(6) NOT NULL DEFAULT '1',
  `job_price` float(7,2) NOT NULL DEFAULT '0.00',
  `price_used` tinyint(1) NOT NULL DEFAULT '0',
  `price_reason` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_order` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `survey_numlevels` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `survey_numalarms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `survey_ceiling` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `survey_alarmspositioned` tinyint(1) DEFAULT NULL,
  `survey_ladder` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `survey_minstandard` tinyint(1) DEFAULT NULL,
  `ts_additionalnotes` text COLLATE utf8mb4_unicode_ci,
  `ts_techconfirm` tinyint(1) DEFAULT '0',
  `ts_signoffdate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_batteriesinstalled` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_alarmsinstalled` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_completed` tinyint(1) NOT NULL DEFAULT '0',
  `ts_noshow` tinyint(1) NOT NULL DEFAULT '0',
  `client_emailed` timestamp NULL DEFAULT NULL,
  `is_eo` tinyint(1) NOT NULL,
  `staff_id` int(11) NOT NULL DEFAULT '0',
  `ts_doorknock` tinyint(1) NOT NULL DEFAULT '0',
  `alarms_synced` tinyint(1) NOT NULL DEFAULT '0',
  `key_access_required` tinyint(1) NOT NULL DEFAULT '0',
  `ts_items_tested` smallint(3) DEFAULT NULL,
  `ts_safety_switch` tinyint(4) NOT NULL DEFAULT '0',
  `entry_notice_emailed` timestamp NULL DEFAULT NULL,
  `ss_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ss_quantity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tmh_id` int(11) DEFAULT NULL,
  `tmh_imported` timestamp NULL DEFAULT NULL,
  `ts_db_reading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ts_rfc` tinyint(1) NOT NULL DEFAULT '0',
  `ts_safety_switch_reason` tinyint(4) DEFAULT NULL,
  `service` int(11) NOT NULL COMMENT 'alarm_job_type id',
  `job_reason_id` int(11) NOT NULL,
  `job_reason_comment` varchar(350) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urgent_job` tinyint(1) NOT NULL,
  `urgent_job_reason` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed_timestamp` datetime DEFAULT NULL,
  `ss_sync` tinyint(4) NOT NULL,
  `cw_sync` tinyint(4) NOT NULL,
  `wm_sync` tinyint(4) DEFAULT NULL,
  `cw_techconfirm` tinyint(4) NOT NULL,
  `ss_techconfirm` tinyint(4) NOT NULL,
  `wm_techconfirm` tinyint(4) DEFAULT NULL,
  `cw_items_tested` smallint(6) DEFAULT NULL,
  `ss_items_tested` smallint(6) DEFAULT NULL,
  `door_knock` tinyint(4) NOT NULL,
  `due_date` date DEFAULT NULL,
  `key_access_details` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tech_notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_date` date DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `del_job` tinyint(1) NOT NULL DEFAULT '0',
  `booked_with` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booked_by` int(11) DEFAULT NULL COMMENT 'staff id',
  `sms_sent` datetime DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `unavailable` tinyint(4) DEFAULT NULL,
  `unavailable_date` date DEFAULT NULL,
  `at_myob` tinyint(4) NOT NULL,
  `no_dates_provided` tinyint(4) NOT NULL,
  `job_entry_notice` tinyint(4) NOT NULL,
  `agency_approve_en` tinyint(4) DEFAULT NULL,
  `ts_ic_alarm_confirm` tinyint(4) DEFAULT NULL,
  `ps_number_of_bedrooms` int(11) DEFAULT NULL,
  `ps_qld_leg_num_alarm` int(11) DEFAULT NULL,
  `preferred_time` varchar(31) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `swms_heights` tinyint(4) DEFAULT NULL,
  `swms_uv_protection` tinyint(4) DEFAULT NULL,
  `swms_asbestos` tinyint(4) DEFAULT NULL,
  `swms_powertools` tinyint(4) DEFAULT NULL,
  `swms_animals` tinyint(4) DEFAULT NULL,
  `swms_live_circuit` tinyint(4) DEFAULT NULL,
  `status_changed_timestamp` timestamp NULL DEFAULT NULL,
  `allocate_timestamp` timestamp NULL DEFAULT NULL,
  `allocate_opt` tinyint(4) DEFAULT NULL,
  `allocate_notes` text COLLATE utf8mb4_unicode_ci,
  `allocated_by` int(11) DEFAULT NULL,
  `allocate_response` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ss_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_vacant` tinyint(4) DEFAULT NULL,
  `dha_need_processing` tinyint(4) DEFAULT NULL,
  `out_of_tech_hours` tinyint(4) DEFAULT NULL,
  `call_before` tinyint(4) DEFAULT NULL,
  `call_before_txt` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precomp_jobs_moved_to_booked` tinyint(4) DEFAULT NULL,
  `trk_kar` tinyint(4) DEFAULT NULL COMMENT 'Tech Run Keys - Key Access Required Marker',
  `trk_tech` int(11) DEFAULT NULL,
  `trk_date` date DEFAULT NULL,
  `tkr_approved_by` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_sent_merge` datetime DEFAULT NULL,
  `sms_sent_no_show` datetime DEFAULT NULL,
  `entry_gained_via` tinyint(4) DEFAULT NULL,
  `entry_gained_other_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_as_paid` tinyint(1) DEFAULT NULL,
  `to_be_printed` tinyint(1) DEFAULT NULL,
  `is_printed` tinyint(1) DEFAULT NULL,
  `rebooked_no_show` tinyint(4) DEFAULT NULL,
  `repair_notes` text COLLATE utf8mb4_unicode_ci,
  `qld_upgrade_quote_emailed` timestamp NULL DEFAULT NULL,
  `job_priority` tinyint(4) DEFAULT NULL,
  `mm_need_proc_inv_emailed` timestamp NULL DEFAULT NULL,
  `invoice_amount` decimal(15,2) DEFAULT NULL,
  `invoice_payments` decimal(15,2) DEFAULT NULL,
  `invoice_credits` decimal(15,2) DEFAULT NULL,
  `invoice_balance` decimal(15,2) DEFAULT NULL,
  `booked_with_new` varchar(31) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_refunds` decimal(15,2) DEFAULT NULL,
  `access_notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bne_to_call_notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_tech` int(11) DEFAULT NULL COMMENT 'staff_accounts',
  `en_date_issued` date DEFAULT NULL,
  `cancelled_date` date DEFAULT NULL,
  `deleted_date` date DEFAULT NULL,
  `preferred_time_ts` datetime DEFAULT NULL,
  `unpaid` tinyint(4) NOT NULL DEFAULT '0',
  `rebooked_show_on_keys` tinyint(4) NOT NULL DEFAULT '0',
  `is_pme_invoice_upload` tinyint(4) DEFAULT NULL,
  `is_pme_bill_create` tinyint(4) DEFAULT NULL,
  `is_palace_invoice_upload` tinyint(4) DEFAULT NULL,
  `is_palace_bill_create` tinyint(1) DEFAULT NULL,
  `property_leaks` tinyint(4) DEFAULT NULL,
  `we_techconfirm` tinyint(4) DEFAULT NULL,
  `we_items_tested` tinyint(4) DEFAULT NULL,
  `leak_notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `we_sync` tinyint(4) DEFAULT NULL,
  `swms_covid_19` tinyint(4) DEFAULT NULL,
  `prop_comp_with_state_leg` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date_idx` (`date`),
  KEY `property_id_idx` (`property_id`),
  KEY `status_idx` (`status`),
  KEY `job_type_idx` (`job_type`),
  KEY `invoice_balance_idx` (`invoice_balance`),
  KEY `job_price_idx` (`job_price`),
  KEY `invoice_amount_idx` (`invoice_amount`),
  KEY `assigned_tech_idx` (`assigned_tech`),
  KEY `created_idx` (`created`),
  KEY `preferred_time_idx` (`preferred_time`),
  KEY `ix_del_job` (`del_job`),
  KEY `jobs_service_idx` (`service`),
  KEY `idx_job_reason` (`job_reason_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1325884 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs_not_completed`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs_not_completed` (
  `jobs_not_completed_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `reason_id` int(11) DEFAULT NULL,
  `reason_comment` mediumtext COLLATE utf8mb4_unicode_ci,
  `tech_id` int(11) DEFAULT NULL,
  `door_knock` tinyint(4) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`jobs_not_completed_id`)
) ENGINE=InnoDB AUTO_INCREMENT=240165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `key_access_cron_report`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `key_access_cron_report` (
  `key_access_cron_report_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `email_to_agency` mediumtext COLLATE utf8mb4_unicode_ci,
  `email_cc_tenants` mediumtext COLLATE utf8mb4_unicode_ci,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`key_access_cron_report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `key_routes`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `key_routes` (
  `key_routes_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `action` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `deleted` int(11) DEFAULT NULL,
  `completed` int(11) DEFAULT NULL,
  `completed_date` datetime DEFAULT NULL,
  `agency_staff` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `number_of_keys` int(11) DEFAULT NULL,
  PRIMARY KEY (`key_routes_id`),
  KEY `key_routes_idx` (`tech_id`,`agency_id`,`deleted`,`completed`)
) ENGINE=InnoDB AUTO_INCREMENT=9945 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kms`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kms` (
  `kms_id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicles_id` int(11) NOT NULL,
  `kms` int(11) NOT NULL,
  `kms_updated` datetime NOT NULL,
  `roof_ladder_secured` tinyint(4) DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`kms_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ladder_check`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ladder_check` (
  `ladder_check_id` int(11) NOT NULL AUTO_INCREMENT,
  `tools_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `inspection_due` date DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ladder_check_id`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ladder_inspection`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ladder_inspection` (
  `ladder_inspection_id` int(11) NOT NULL AUTO_INCREMENT,
  `item` mediumtext COLLATE utf8mb4_unicode_ci,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`ladder_inspection_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ladder_inspection_selection`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ladder_inspection_selection` (
  `ladder_inspection_selection_id` int(11) NOT NULL AUTO_INCREMENT,
  `ladder_check_id` int(11) DEFAULT NULL,
  `ladder_inspection_id` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ladder_inspection_selection_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1971 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `last_contact_comments`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `last_contact_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `created_date` datetime DEFAULT NULL,
  `last_update_date` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=496 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `leave`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave` (
  `leave_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `employee` int(11) DEFAULT NULL,
  `type_of_leave` int(11) DEFAULT NULL,
  `lday_of_work` date DEFAULT NULL,
  `fday_back` date DEFAULT NULL,
  `num_of_days` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason_for_leave` mediumtext COLLATE utf8mb4_unicode_ci,
  `line_manager` int(11) DEFAULT NULL,
  `line_manager_app` int(11) DEFAULT NULL,
  `line_manager_app_by` int(11) DEFAULT NULL,
  `line_manager_app_timestamp` datetime DEFAULT NULL,
  `hr_app` int(11) DEFAULT NULL,
  `hr_app_by` int(11) DEFAULT NULL,
  `hr_app_timestamp` datetime DEFAULT NULL,
  `added_to_cal` int(11) DEFAULT NULL,
  `added_to_cal_by` int(11) DEFAULT NULL,
  `added_to_cal_timestamp` datetime DEFAULT NULL,
  `staff_notified` int(11) DEFAULT NULL,
  `staff_notified_by` int(11) DEFAULT NULL,
  `staff_notified_timestamp` datetime DEFAULT NULL,
  `comments` mediumtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `country_id` int(11) DEFAULT '1',
  `backup_leave` int(11) DEFAULT NULL,
  PRIMARY KEY (`leave_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4538 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `leave_types`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_types` (
  `leave_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hidden` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`leave_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `leaving_reason`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leaving_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_on` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lockout_kit_check`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lockout_kit_check` (
  `lockout_kit_check_id` int(11) NOT NULL AUTO_INCREMENT,
  `tools_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `inspection_due` date DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`lockout_kit_check_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lockout_kit_checklist`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lockout_kit_checklist` (
  `lockout_kit_checklist_id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`lockout_kit_checklist_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lockout_kit_checklist_selection`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lockout_kit_checklist_selection` (
  `lockout_kit_checklist_selection_id` int(11) NOT NULL AUTO_INCREMENT,
  `lockout_kit_check_id` int(11) DEFAULT NULL,
  `lockout_kit_checklist_id` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`lockout_kit_checklist_selection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_title_usable_pages`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_title_usable_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_titles_id` int(11) DEFAULT NULL,
  `show_in` int(11) DEFAULT NULL COMMENT 'VJD=1,VPD=2,VAD=3',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_titles`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_titles` (
  `log_title_id` int(11) NOT NULL AUTO_INCREMENT,
  `title_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`log_title_id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `logged_page_durations`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logged_page_durations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(44) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10093331 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `logs`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` int(11) DEFAULT NULL COMMENT 'log_title_id',
  `details` mediumtext COLLATE utf8mb4_unicode_ci,
  `log_type` int(11) DEFAULT NULL COMMENT 'main_log_type_id',
  `display_in_vjd` int(11) NOT NULL,
  `display_in_vpd` int(11) NOT NULL,
  `display_in_vad` int(11) NOT NULL,
  `display_in_portal` int(11) NOT NULL,
  `display_in_accounts` int(11) NOT NULL,
  `display_in_accounts_hid` int(11) NOT NULL,
  `display_in_sales` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_by` int(11) DEFAULT NULL,
  `auto_process` tinyint(4) NOT NULL,
  `created_by_staff` int(11) DEFAULT NULL,
  `important` int(11) DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `job_id_idx` (`job_id`),
  KEY `property_id_idx` (`property_id`),
  KEY `created_by_idx` (`created_by`),
  KEY `agency_id_idx` (`agency_id`),
  KEY `title` (`title`),
  KEY `created_by_staff` (`created_by_staff`),
  KEY `log_id` (`log_id`,`display_in_vjd`,`display_in_vpd`,`display_in_vad`,`display_in_portal`,`display_in_accounts`,`job_id`,`property_id`,`agency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8863746 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `main_log_type`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `main_log_type` (
  `main_log_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_show` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'flag for Agency status',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`main_log_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `main_page_total`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `main_page_total` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int(11) NOT NULL DEFAULT '0',
  `total_goal` int(11) NOT NULL DEFAULT '0',
  `staff_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maintenance`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maintenance` (
  `maintenance_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`maintenance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `map_routes`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `map_routes` (
  `map_routes_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL COMMENT 'job date',
  `start` int(11) DEFAULT NULL COMMENT 'accomodation id',
  `end` int(11) DEFAULT NULL COMMENT 'accomodation id',
  `sorted` int(11) DEFAULT NULL,
  `run_set` int(11) DEFAULT NULL,
  `run_mapped` int(11) DEFAULT NULL,
  `run_complete` int(11) DEFAULT NULL,
  `run_approved` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`map_routes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3417 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_class` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_index` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `icon_class_new` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu_permission_class`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_permission_class` (
  `mpc_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` int(11) DEFAULT NULL,
  `staff_class` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`mpc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu_permission_user`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_permission_user` (
  `mpu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `denied` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`mpu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_header_id` int(11) DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime DEFAULT NULL,
  `read` int(11) NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `message_header_id_idx` (`message_header_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46963 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_group`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_group` (
  `message_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_header_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `read` int(11) DEFAULT NULL,
  `read_date` datetime DEFAULT NULL,
  PRIMARY KEY (`message_group_id`),
  KEY `message_header_id_idx` (`message_header_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47709 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_header`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_header` (
  `message_header_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `read` int(11) DEFAULT NULL,
  `read_date` datetime DEFAULT NULL,
  `status` int(11) NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_header_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_read_by`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_read_by` (
  `message_read_by_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `read` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`message_read_by_id`),
  KEY `message_id_idx` (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46606 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `next_service_exclude_agency`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `next_service_exclude_agency` (
  `nsea_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`nsea_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `noticeboard`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `noticeboard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notice` mediumtext COLLATE utf8mb4_unicode_ci,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifications`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `notifications_id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notify_to` int(11) DEFAULT NULL,
  `read` tinyint(4) NOT NULL DEFAULT '0',
  `notf_type` int(11) NOT NULL DEFAULT '1' COMMENT '1 for general, 2 for sms',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`notifications_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1187543 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nsw_property_compliance`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nsw_property_compliance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `short_term_rental_compliant` tinyint(4) DEFAULT NULL,
  `req_num_alarms` int(11) DEFAULT NULL,
  `req_heat_alarm` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=413 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `options`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `options` (
  `option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `price` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pricing_structure` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `other_property_details`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `other_property_details` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `building_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=474 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page_permissions`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_permissions` (
  `PermissionID` int(11) NOT NULL AUTO_INCREMENT,
  `PageID` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL,
  PRIMARY KEY (`PermissionID`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page_total`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_total` (
  `page_total_id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`page_total_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pages`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `PageID` int(11) NOT NULL AUTO_INCREMENT,
  `Filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`PageID`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_types`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_types` (
  `payment_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `pt_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_num` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`payment_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission_list`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pme_unmatched_property_count`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pme_unmatched_property_count` (
  `pme_upc_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pme_upc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=468 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `postcode`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postcode` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_region_id` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `postcode` (`postcode`)
) ENGINE=InnoDB AUTO_INCREMENT=4700 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `postcode_regions`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postcode_regions` (
  `postcode_region_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) DEFAULT NULL,
  `region` int(11) DEFAULT NULL,
  `postcode_region_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode_region_postcodes` mediumtext COLLATE utf8mb4_unicode_ci,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`postcode_region_id`)
) ENGINE=InnoDB AUTO_INCREMENT=340 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `price_increase_excluded_agency`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `price_increase_excluded_agency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `exclude_until` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `properties_from_other_company`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `properties_from_other_company` (
  `pfoc_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `added_date` date DEFAULT NULL,
  PRIMARY KEY (`pfoc_id`),
  KEY `property_id_idx` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=132803 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `properties_needs_verification`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `properties_needs_verification` (
  `pnv_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_source` int(11) DEFAULT NULL,
  `property_id` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_address` mediumtext COLLATE utf8mb4_unicode_ci,
  `agency_id` int(11) DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `agency_verified` tinyint(4) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `ignore_issue` tinyint(4) NOT NULL,
  `last_contact_info` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`pnv_id`),
  KEY `idx_pnv_columns` (`property_source`,`property_id`,`agency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15837 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `properties_tracked`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `properties_tracked` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `gained_or_lost` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=145736 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property` (
  `property_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `address_1` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_2` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_3` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(31) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a1_type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a1_exp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a2_type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a2_exp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a3_type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a3_exp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a4_type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a4_exp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a5_type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a5_exp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a6_type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a6_exp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testing_comments` mediumtext COLLATE utf8mb4_unicode_ci,
  `new_alarms_installed` int(10) unsigned DEFAULT NULL,
  `authority_recd` date DEFAULT NULL,
  `tenant_ltr_sent` date DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `phone_booking` date DEFAULT NULL,
  `test_date` date DEFAULT NULL,
  `booking_comments` mediumtext COLLATE utf8mb4_unicode_ci,
  `inv_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `retest_date` date DEFAULT NULL,
  `agency_id` int(10) unsigned NOT NULL,
  `tenant_firstname1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_lastname1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_firstname2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_lastname2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_ph1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_ph2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_email1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_email2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_mob1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_mob2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landlord_ph` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landlord_firstname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landlord_lastname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agentname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `a1_pwr` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a2_pwr` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a3_pwr` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a4_pwr` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a5_pwr` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a6_pwr` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a1_make` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a2_make` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a3_make` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a4_make` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a5_make` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a6_make` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a1_model` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a2_model` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a3_model` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a4_model` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a5_model` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a6_model` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` float(5,2) unsigned DEFAULT NULL,
  `service` tinyint(1) DEFAULT '1',
  `yearpurchase` year(4) DEFAULT NULL,
  `agency_deleted` tinyint(1) DEFAULT '0',
  `sa_restore` tinyint(1) DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `price_old` int(6) DEFAULT NULL,
  `alt_property_id` int(11) DEFAULT NULL,
  `tmh_imported` timestamp NULL DEFAULT NULL,
  `key_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rfc` tinyint(1) DEFAULT '0',
  `landlord_email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_visit` datetime DEFAULT NULL,
  `added_by` int(11) NOT NULL COMMENT 'StaffID',
  `deleted_date` datetime NOT NULL,
  `property_managers_id` int(11) NOT NULL,
  `tenant_changed` datetime NOT NULL,
  `status_changed` datetime DEFAULT NULL,
  `lat` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lng` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `holiday_rental` int(11) DEFAULT NULL,
  `no_keys` int(11) DEFAULT NULL,
  `alarm_code` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landlord_mob` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qld_new_leg_alarm_num` int(11) DEFAULT NULL,
  `no_en` int(11) DEFAULT NULL COMMENT 'No Entry Notice',
  `is_nlm` tinyint(4) NOT NULL DEFAULT '0',
  `tenant_firstname3` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_lastname3` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_mob3` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_ph3` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_email3` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_firstname4` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_lastname4` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_mob4` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_ph4` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_email4` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nlm_timestamp` datetime DEFAULT NULL,
  `nlm_by_sats_staff` int(11) DEFAULT NULL,
  `nlm_by_agency` int(11) DEFAULT NULL,
  `nlm_display` tinyint(4) DEFAULT NULL,
  `nlm_owing` tinyint(4) DEFAULT NULL,
  `prop_upgraded_to_ic_sa` int(11) DEFAULT NULL,
  `propertyme_prop_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `move_tenants_sync` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes',
  `compass_index_num` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_tenants_sync` tinyint(4) NOT NULL DEFAULT '0',
  `pm_id_new` int(11) DEFAULT NULL,
  `bne_to_call` int(11) NOT NULL,
  `qld_upgrade_quote_approved_ts` datetime DEFAULT NULL,
  `write_off` int(11) DEFAULT NULL,
  `staff_marked_done` int(11) NOT NULL DEFAULT '0' COMMENT 'temporary field',
  `no_dk` tinyint(4) NOT NULL DEFAULT '0',
  `ignore_dirty_address` int(11) NOT NULL DEFAULT '0',
  `palace_prop_id` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_unserviced` int(1) NOT NULL DEFAULT '0',
  `is_sales` tinyint(4) NOT NULL,
  `ourtradie_prop_id` mediumtext COLLATE utf8mb4_unicode_ci,
  `send_to_email_not_api` tinyint(4) NOT NULL,
  `postpone_due_job` datetime DEFAULT NULL,
  `requires_ppe` tinyint(4) NOT NULL,
  `manual_renewal` tinyint(4) NOT NULL,
  `preferred_alarm_id` int(11) DEFAULT NULL,
  `subscription_billed` tinyint(4) NOT NULL,
  `service_garage` tinyint(4) NOT NULL,
  `retest_timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`property_id`),
  KEY `agency_id_idx` (`agency_id`),
  KEY `pm_id_new_idx` (`pm_id_new`),
  KEY `address_idx` (`address_1`,`address_2`,`address_3`),
  KEY `postcode_idx` (`postcode`),
  KEY `ix_deleted_agency` (`deleted`,`agency_id`),
  KEY `idx_nlm_property` (`is_nlm`)
) ENGINE=InnoDB AUTO_INCREMENT=403546 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_alarms`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_alarms` (
  `property_alarms_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `alarm_pwr_id` int(11) NOT NULL,
  `price` float(5,2) NOT NULL,
  PRIMARY KEY (`property_alarms_id`),
  KEY `property_id_idx` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=482765 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_completed_increase`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_completed_increase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `submitted_by` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7184 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_event_log`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_event_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `event_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_details` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `log_date` datetime DEFAULT NULL,
  `important` int(11) DEFAULT NULL,
  `log_agency_id` int(11) DEFAULT NULL,
  `hide_delete` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `property_id_idx` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1493273 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_files`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_files` (
  `property_files_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `path` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`property_files_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26477 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_keys`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `agency_addresses_id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_lockbox`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_lockbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8751 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_managers`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_managers` (
  `property_managers_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pm_email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agency_id` int(11) NOT NULL,
  PRIMARY KEY (`property_managers_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1062 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_nlm_reason`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_nlm_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `reason` int(11) DEFAULT NULL,
  `other_reason` text COLLATE utf8mb4_unicode_ci,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33372 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_propertytype`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_propertytype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `alarm_job_type_id` int(11) DEFAULT NULL,
  `tmh_imported` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `property_id_idx` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=689194 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_services`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_services` (
  `property_services_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `alarm_job_type_id` int(11) NOT NULL,
  `service` int(11) NOT NULL,
  `price` float(5,2) NOT NULL,
  `status_changed` datetime DEFAULT NULL,
  `last_inspection` datetime DEFAULT NULL,
  `is_payable` tinyint(4) NOT NULL,
  PRIMARY KEY (`property_services_id`),
  KEY `property_id_idx` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=637297 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_subscription`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_subscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `subscription_date` date DEFAULT NULL,
  `source` int(11) DEFAULT NULL,
  `date_updated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ps_property_id` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=103566 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_tenants`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_tenants` (
  `property_tenant_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `tenant_firstname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_lastname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_mobile` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_landline` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_worknumber` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_tenant_id` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'ID from propertyme api',
  `createdDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedDate` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-InActive; 1-Active',
  `tenant_priority` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`property_tenant_id`),
  KEY `property_id_idx` (`property_id`),
  KEY `tenant_mobile_idx` (`tenant_mobile`),
  KEY `tenant_landline_idx` (`tenant_landline`)
) ENGINE=InnoDB AUTO_INCREMENT=664904 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_variation`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_variation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `agency_price_variation` int(11) DEFAULT NULL,
  `date_applied` date DEFAULT NULL,
  `deleted_ts` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `property_id_idx` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21253 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `propertytree_agency_preference`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `propertytree_agency_preference` (
  `pt_ap_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `agent` text COLLATE utf8mb4_unicode_ci COMMENT 'http header: x_user_id',
  `creditor` text COLLATE utf8mb4_unicode_ci,
  `account_code` int(11) DEFAULT NULL,
  `prop_comp_cat` text COLLATE utf8mb4_unicode_ci COMMENT 'Property Compliance Category',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pt_ap_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `propertytree_app_key_pairs`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `propertytree_app_key_pairs` (
  `pd_akp_id` int(11) NOT NULL AUTO_INCREMENT,
  `authentication_key` text COLLATE utf8mb4_unicode_ci COMMENT 'API key/token',
  `company_name` text COLLATE utf8mb4_unicode_ci,
  `activation_date` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pd_akp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `purchase_order`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order` (
  `purchase_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_num` mediumtext COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `suppliers_id` int(11) DEFAULT NULL,
  `item_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `deliver_to` int(11) DEFAULT NULL COMMENT 'staff_accounts',
  `comments` mediumtext COLLATE utf8mb4_unicode_ci,
  `ordered_by` int(11) DEFAULT NULL COMMENT 'staff_accounts',
  `agency_id` int(11) DEFAULT NULL,
  `invoice_total` decimal(20,2) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `receiver_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`purchase_order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5502 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `purchase_order_item`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order_item` (
  `purchase_order_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int(11) DEFAULT NULL,
  `stocks_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total` decimal(20,2) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`purchase_order_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35752 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quote_alarms`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quote_alarms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_pwr_id` int(11) NOT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `regions`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `regions` (
  `regions_id` int(11) NOT NULL AUTO_INCREMENT,
  `region_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_state` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`regions_id`),
  KEY `region_state_idx` (`region_state`(30))
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `renewal_type`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `renewal_type` (
  `renewal_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`renewal_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `renewals`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `renewals` (
  `renewals_id` int(11) NOT NULL AUTO_INCREMENT,
  `StaffID` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `num_jobs_created` int(11) NOT NULL,
  `renewal_type` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`renewals_id`)
) ENGINE=InnoDB AUTO_INCREMENT=238 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `resources`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resources` (
  `resources_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `filename` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `states` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `resources_header_id` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`resources_id`)
) ENGINE=InnoDB AUTO_INCREMENT=391 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `resources_header`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resources_header` (
  `resources_header_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`resources_header_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `safety_switch`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `safety_switch` (
  `safety_switch_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `make` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `test` int(11) DEFAULT NULL,
  `new` int(11) NOT NULL,
  `ss_stock_id` int(11) DEFAULT NULL,
  `ss_res_id` int(11) DEFAULT NULL,
  `discarded` tinyint(4) NOT NULL,
  PRIMARY KEY (`safety_switch_id`),
  KEY `ss_idx` (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=484599 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `safety_switch_reason`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `safety_switch_reason` (
  `ss_res_id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ss_res_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `safety_switch_stock`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `safety_switch_stock` (
  `ss_stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `pole` int(11) DEFAULT NULL,
  `make` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buy_price` decimal(12,2) NOT NULL COMMENT 'inc GST',
  `sell_price` decimal(12,2) NOT NULL COMMENT 'inc GST',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ss_stock_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Safety Switch Main table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_documents`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_documents` (
  `sales_documents_id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `states` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_documents_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_report`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_type` int(11) DEFAULT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  `staff_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `next_contact` date DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=119215 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_snapshot`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_snapshot` (
  `sales_snapshot_id` int(11) NOT NULL AUTO_INCREMENT,
  `agency` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `properties` int(11) NOT NULL,
  `area` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sales_snapshot_status_id` int(11) NOT NULL,
  `details` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `sales_snapshot_sales_rep_id` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_snapshot_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1573 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_snapshot_sales_rep`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_snapshot_sales_rep` (
  `sales_snapshot_sales_rep_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_snapshot_sales_rep_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_snapshot_status`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_snapshot_status` (
  `sales_snapshot_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_snapshot_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `selected_escalate_job_reasons`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `selected_escalate_job_reasons` (
  `selected_escalate_job_reasons_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `escalate_job_reasons_id` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`selected_escalate_job_reasons_id`)
) ENGINE=InnoDB AUTO_INCREMENT=172238 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `expiry` datetime NOT NULL,
  `agency_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `short_term_service_price`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `short_term_service_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_type` int(11) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `state` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_accounts`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_accounts` (
  `site_accounts_id` int(11) NOT NULL AUTO_INCREMENT,
  `website` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` mediumtext COLLATE utf8mb4_unicode_ci,
  `notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `expiry_date` date DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`site_accounts_id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `smoke_alarms`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smoke_alarms` (
  `smoke_alarm_id` int(11) NOT NULL AUTO_INCREMENT,
  `make` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `power_type` int(11) DEFAULT NULL,
  `detection_type` int(11) DEFAULT NULL,
  `expiry_manuf_date` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loc_of_date` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remove_battery` int(11) DEFAULT NULL,
  `hush_button` int(11) DEFAULT NULL,
  `common_faults` mediumtext COLLATE utf8mb4_unicode_ci,
  `how_to_rem_al` mediumtext COLLATE utf8mb4_unicode_ci,
  `adntl_notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `front_image` mediumtext COLLATE utf8mb4_unicode_ci,
  `rear_image_1` mediumtext COLLATE utf8mb4_unicode_ci,
  `rear_image_2` mediumtext COLLATE utf8mb4_unicode_ci,
  `country_id` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`smoke_alarm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `smoke_alarms_company`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smoke_alarms_company` (
  `sac_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sac_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_api_replies`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_api_replies` (
  `sms_api_replies_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datetime_entry` datetime DEFAULT NULL,
  `response` mediumtext COLLATE utf8mb4_unicode_ci,
  `longcode` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unread` int(11) DEFAULT '1',
  `saved` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `sms_replied_to` int(5) NOT NULL DEFAULT '0',
  `sms_ref_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sms_api_replies_id`),
  KEY `message_id_idx` (`message_id`),
  KEY `created_date_idx` (`created_date`)
) ENGINE=InnoDB AUTO_INCREMENT=668765 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_api_sent`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_api_sent` (
  `sms_api_sent_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `message_id` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci,
  `mobile` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_at` datetime DEFAULT NULL,
  `sent_by` int(11) DEFAULT NULL,
  `sms_type` int(11) DEFAULT NULL,
  `recipients` int(11) DEFAULT NULL,
  `sms` int(11) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `delivery_stats_delivered` int(11) DEFAULT NULL,
  `delivery_stats_bounced` int(11) DEFAULT NULL,
  `delivery_stats_responses` int(11) DEFAULT NULL,
  `delivery_stats_pending` int(11) DEFAULT NULL,
  `delivery_stats_optouts` int(11) DEFAULT NULL,
  `error_code` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_desc` mediumtext COLLATE utf8mb4_unicode_ci,
  `cb_mobile` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cb_datetime` datetime DEFAULT NULL,
  `cb_status` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sms_api_sent_id`),
  KEY `job_id_idx` (`job_id`),
  KEY `message_id_idx` (`message_id`),
  KEY `created_date_idx` (`created_date`),
  KEY `active` (`active`),
  KEY `sent_by` (`sent_by`,`sms_type`)
) ENGINE=InnoDB AUTO_INCREMENT=3446343 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_api_type`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_api_type` (
  `sms_api_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `category` mediumtext COLLATE utf8mb4_unicode_ci,
  `body` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`sms_api_type_id`),
  KEY `sms_api_type_idx` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_messages`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_messages` (
  `sms_messages_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sms_messages_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_number_reference`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_number_reference` (
  `sms_ref_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sms_ref_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_accounts`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_accounts` (
  `StaffID` int(11) NOT NULL AUTO_INCREMENT,
  `ClassID` int(11) NOT NULL DEFAULT '6',
  `Email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FirstName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LastName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Hash` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ContactNumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `TechID` int(11) DEFAULT NULL,
  `PasswordDecoded` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `tmh_id` int(11) DEFAULT NULL,
  `dob` date NOT NULL,
  `phone_model_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_serial_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_imei` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_pin` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_id_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_key_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_plant_id` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_shirt_size` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ipad_model_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ipad_serial_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ipad_imei` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tablet_pin` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ipad_prepaid_serv_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `laptop_make` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `laptop_serial_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `debit_card_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ipad_expiry_date` date DEFAULT NULL,
  `dha_card` int(11) DEFAULT NULL,
  `debit_expiry_date` date DEFAULT NULL,
  `booking_schedule_num` int(11) DEFAULT NULL,
  `working_days` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_num` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sa_position` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sound_notification` tinyint(4) NOT NULL DEFAULT '0',
  `other_call_centre` int(11) DEFAULT NULL,
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accomodation_id` int(11) DEFAULT NULL,
  `electrical_license` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_license` mediumtext COLLATE utf8mb4_unicode_ci,
  `blue_card_num` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blue_card_expiry` date DEFAULT NULL,
  `licence_expiry` date DEFAULT NULL,
  `home_display_precomp` tinyint(4) NOT NULL DEFAULT '1',
  `home_display_car_n_tools` tinyint(4) NOT NULL DEFAULT '1',
  `home_display_leave` tinyint(4) NOT NULL DEFAULT '1',
  `home_display_expenses` tinyint(4) NOT NULL DEFAULT '1',
  `home_display_staff` tinyint(4) NOT NULL DEFAULT '1',
  `home_display_resources` tinyint(4) NOT NULL DEFAULT '1',
  `home_display_notice_board` tinyint(4) NOT NULL DEFAULT '1',
  `ice_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ice_phone` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_new` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `elec_license_num` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_license_num` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `elec_licence_expiry` date DEFAULT NULL,
  `is_electrician` tinyint(4) NOT NULL,
  `homepage_view` int(11) DEFAULT NULL,
  `display_on_wsr` int(11) NOT NULL COMMENT 'Display on Weekly Sales Report',
  `recieve_wsr` int(11) NOT NULL COMMENT 'Recieve Weekly Sales Report',
  `personal_contact_number` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`StaffID`),
  KEY `ix_staff_accounts_class_id` (`ClassID`)
) ENGINE=InnoDB AUTO_INCREMENT=2588 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_classes`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_classes` (
  `ClassID` int(11) NOT NULL AUTO_INCREMENT,
  `ClassName` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_index` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ClassID`),
  KEY `ix_staff_classes` (`ClassName`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_permissions`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL,
  `has_permission_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_states`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_states` (
  `PermissionID` int(11) NOT NULL AUTO_INCREMENT,
  `StaffID` int(11) NOT NULL,
  `StateID` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`PermissionID`)
) ENGINE=InnoDB AUTO_INCREMENT=19170 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `states_def`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `states_def` (
  `StateID` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `state_full_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`StateID`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `static_sales_service_price`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `static_sales_service_price` (
  `sssp_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_type` int(11) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sssp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stocks`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stocks` (
  `stocks_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` float DEFAULT NULL,
  `display` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `sort_index` int(11) DEFAULT NULL,
  `suppliers_id` int(11) DEFAULT NULL,
  `show_on_stocktake` int(11) NOT NULL,
  `carton` int(11) DEFAULT NULL,
  `is_alarm` tinyint(4) NOT NULL,
  PRIMARY KEY (`stocks_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sub_regions`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_regions` (
  `sub_region_id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) DEFAULT NULL,
  `subregion_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sub_region_id`),
  KEY `region_id_idx` (`region_id`)
) ENGINE=InnoDB AUTO_INCREMENT=437 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subscription_source`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscription_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `suppliers`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `suppliers_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_provided` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci,
  `lat` mediumtext COLLATE utf8mb4_unicode_ci,
  `lng` mediumtext COLLATE utf8mb4_unicode_ci,
  `contact_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `created` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `country_id` int(11) DEFAULT NULL,
  `on_map` int(11) NOT NULL DEFAULT '0',
  `sales_agreement_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`suppliers_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_breaks`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_breaks` (
  `tech_break_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_id` int(11) DEFAULT NULL,
  `tech_break_start` datetime DEFAULT NULL,
  `tech_break_taken` tinyint(4) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`tech_break_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9462 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_doc_header`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_doc_header` (
  `tech_doc_header_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tech_doc_header_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_locations`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_id` int(11) NOT NULL,
  `lat` decimal(11,7) NOT NULL,
  `lng` decimal(11,7) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=345180 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_regions`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `breakpoint` int(11) NOT NULL,
  `techs_needed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_regions_regions`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_regions_regions` (
  `tech_region_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_run`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_run` (
  `tech_run_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL COMMENT 'job date',
  `sub_regions` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start` int(11) DEFAULT NULL COMMENT 'accomodation id',
  `end` int(11) DEFAULT NULL COMMENT 'accomodation id',
  `sorted` int(11) DEFAULT NULL,
  `run_set` int(11) DEFAULT NULL,
  `run_mapped` int(11) DEFAULT NULL,
  `run_complete` int(11) DEFAULT NULL,
  `run_approved` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `tech_notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `show_hidden` int(11) NOT NULL,
  `ready_to_book` int(11) DEFAULT NULL,
  `finished_booking` int(11) DEFAULT NULL,
  `no_more_jobs` int(11) DEFAULT NULL,
  `display_num` int(11) DEFAULT NULL,
  `agency_filter` mediumtext COLLATE utf8mb4_unicode_ci,
  `notes_updated_ts` datetime DEFAULT NULL,
  `notes_updated_by` int(11) DEFAULT NULL,
  `run_coloured` int(11) DEFAULT NULL,
  `first_call_over_done` int(11) DEFAULT NULL,
  `run_reviewed` int(11) DEFAULT NULL,
  `additional_call_over` int(11) DEFAULT NULL,
  `additional_call_over_done` int(11) DEFAULT NULL,
  `assigned_tech` int(11) DEFAULT NULL COMMENT 'staff_accounts',
  `ready_to_map` tinyint(4) NOT NULL,
  `morning_call_over` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `working_hours` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`tech_run_id`),
  KEY `tech_run_idx` (`date`,`sub_regions`(191),`country_id`,`assigned_tech`)
) ENGINE=InnoDB AUTO_INCREMENT=57437 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_run_hide_job_types`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_run_hide_job_types` (
  `trjt_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_run_id` int(11) DEFAULT NULL,
  `job_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`trjt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_run_keys`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_run_keys` (
  `tech_run_keys_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `action` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `deleted` int(11) DEFAULT NULL,
  `completed` int(11) DEFAULT NULL,
  `completed_date` datetime DEFAULT NULL,
  `agency_staff` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `number_of_keys` int(11) DEFAULT NULL,
  `signature_svg` mediumtext COLLATE utf8mb4_unicode_ci,
  `assigned_tech` int(11) DEFAULT NULL COMMENT 'staff_accounts',
  `refused_sig` tinyint(4) NOT NULL,
  `agency_addresses_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tech_run_keys_id`),
  KEY `agency_id_idx` (`agency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=213163 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_run_logs`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_run_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_run_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132470 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_run_row_color`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_run_row_color` (
  `tech_run_row_color_id` int(11) NOT NULL AUTO_INCREMENT,
  `color` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hex` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`tech_run_row_color_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_run_rows`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_run_rows` (
  `tech_run_rows_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_run_id` int(11) DEFAULT NULL,
  `row_id_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `row_id` int(11) DEFAULT NULL,
  `sort_order_num` int(11) DEFAULT NULL,
  `dnd_sorted` int(11) NOT NULL,
  `highlight_color` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `hidden` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`tech_run_rows_id`),
  KEY `tech_run_id_idx` (`tech_run_id`),
  KEY `row_id_idx` (`row_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11212655 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_run_suppliers`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_run_suppliers` (
  `tech_run_suppliers_id` int(11) NOT NULL AUTO_INCREMENT,
  `suppliers_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tech_run_suppliers_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_stock`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_stock` (
  `tech_stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `vehicle` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tech_stock_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11369 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_stock_items`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_stock_items` (
  `tech_stock_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_stock_id` int(11) DEFAULT NULL,
  `stocks_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`tech_stock_item_id`),
  KEY `tech_stock_id_idx` (`tech_stock_id`)
) ENGINE=InnoDB AUTO_INCREMENT=252421 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tech_working_hours`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_working_hours` (
  `working_hours_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(5) NOT NULL,
  `working_hours` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`working_hours_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `technician_documents`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technician_documents` (
  `technician_documents_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `tech_doc_header_id` int(11) DEFAULT NULL,
  `filename` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`technician_documents_id`)
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `techs`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `techs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ph_mob1` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ph_mob2` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ph_home` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alert_email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `license_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `electrician` tinyint(1) DEFAULT '0',
  `tmh_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`ph_mob1`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test_and_tag`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test_and_tag` (
  `test_and_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tools_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `tnt_completed` int(11) DEFAULT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci,
  `inspection_due` date DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`test_and_tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tool_items`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tool_items` (
  `tool_items_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tool_items_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tools`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tools` (
  `tools_id` int(11) NOT NULL AUTO_INCREMENT,
  `item` int(11) DEFAULT NULL,
  `item_id` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `purchase_date` date DEFAULT NULL,
  `purchase_price` decimal(20,2) DEFAULT NULL,
  `assign_to_vehicle` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tools_id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `trust_account_software`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trust_account_software` (
  `trust_account_software_id` int(11) NOT NULL AUTO_INCREMENT,
  `tsa_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`trust_account_software_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_log`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_log` (
  `user_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `details` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `staff_id` int(11) NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_log_id`),
  KEY `staff_id_idx` (`staff_id`),
  KEY `added_by_idx` (`added_by`)
) ENGINE=InnoDB AUTO_INCREMENT=839 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vaccinations`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vaccinations` (
  `vaccination_id` int(11) NOT NULL AUTO_INCREMENT,
  `StaffID` int(11) NOT NULL,
  `vaccine_brand` tinyint(4) NOT NULL,
  `completed_on` date NOT NULL,
  `valid_till` date DEFAULT NULL,
  `certificate_image` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`vaccination_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicle_files`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_files` (
  `vehicle_files_id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicles_id` int(11) NOT NULL,
  `path` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`vehicle_files_id`)
) ENGINE=InnoDB AUTO_INCREMENT=365 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicle_log_files`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_log_files` (
  `vehicle_log_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_log_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `path` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vehicle_log_file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicles`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicles` (
  `vehicles_id` int(11) NOT NULL AUTO_INCREMENT,
  `make` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `number_plate` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rego_expires` date NOT NULL,
  `warranty_expires` date NOT NULL,
  `fuel_type` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etag_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serviced_by` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `next_service` int(11) NOT NULL,
  `serviced_booked` int(11) DEFAULT NULL,
  `fuel_card_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_date` date NOT NULL,
  `purchase_price` float(9,2) NOT NULL,
  `ra_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ins_pol_num` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `policy_expires` datetime NOT NULL,
  `StaffID` int(11) NOT NULL,
  `fuel_card_pin` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vin_num` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plant_id` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cust_reg_num` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `tech_vehicle` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `key_number` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` mediumtext COLLATE utf8mb4_unicode_ci,
  `engine_number` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finance_bank` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finance_loan_num` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finance_loan_terms` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finance_monthly_repayments` decimal(10,2) DEFAULT NULL,
  `finance_start_date` date DEFAULT NULL,
  `finance_end_date` date DEFAULT NULL,
  `WOF` date DEFAULT NULL,
  `vehicle_ownership` int(11) NOT NULL DEFAULT '1',
  `insurer` mediumtext COLLATE utf8mb4_unicode_ci,
  `transmission` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`vehicles_id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicles_log`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicles_log` (
  `vehicles_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicles_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `price` float(6,2) NOT NULL,
  `details` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `staff_id` int(11) NOT NULL,
  PRIMARY KEY (`vehicles_log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=848 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `warranties`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranties` (
  `warranty_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_staff_id` int(11) DEFAULT NULL,
  `make` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_replaced` int(11) DEFAULT NULL,
  `amount_discarded` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`warranty_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `water_efficiency`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `water_efficiency` (
  `water_efficiency_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `device` int(11) DEFAULT NULL,
  `pass` tinyint(4) DEFAULT NULL,
  `location` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_date` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`water_efficiency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56088 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `water_efficiency_device`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `water_efficiency_device` (
  `water_efficiency_device_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`water_efficiency_device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `water_meter`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `water_meter` (
  `water_meter_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `location` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reading` double DEFAULT NULL,
  `meter_image` mediumtext COLLATE utf8mb4_unicode_ci,
  `meter_reading_image` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_date` datetime DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`water_meter_id`)
) ENGINE=InnoDB AUTO_INCREMENT=924 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `window_material_cw`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `window_material_cw` (
  `window_material_cw_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`window_material_cw_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `window_type_cw`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `window_type_cw` (
  `window_type_cw_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`window_type_cw_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `yaabr_test_reply`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `yaabr_test_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datetime_entry` datetime DEFAULT NULL,
  `json_reponse` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-01-16 12:55:37
