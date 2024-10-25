-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 08:40 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `Phys_id` int(20) NOT NULL,
  `First_name` varchar(50) NOT NULL,
  `Last_name` varchar(50) NOT NULL,
  `Speciality` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`Phys_id`, `First_name`, `Last_name`, `Speciality`) VALUES
(10121, 'Emily', 'Carter', 'Cardiologist'),
(10122, 'Michael ', 'Jhonson', 'Orthopedics'),
(10123, 'Sarah', 'Lee', 'Neurology'),
(10124, 'David', 'Miller', 'Dermatology');

-- --------------------------------------------------------

--
-- Table structure for table `drug`
--

CREATE TABLE `drug` (
  `Drug_Id` int(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Company_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug`
--

INSERT INTO `drug` (`Drug_Id`, `Name`, `Company_id`) VALUES
(987953, 'Ranitidine', 805144),
(1243234, 'Prednisone', 805144),
(3180362, 'Aspirin', 687902),
(4570912, 'Cetirizine', 687902),
(4578797, 'Metformin', 805144),
(4588778, 'Ibuprofen', 790734),
(4816888, 'Simvastatin', 803246),
(5437927, 'Amoxicillin', 875362),
(5439872, 'Omeprazole', 805144),
(5467346, 'Clonazepam', 790734),
(5679327, 'Sertraline', 805144),
(6723459, 'Atorvastatin', 875362),
(6909357, 'Furosemide', 803246),
(7893645, 'Gabapentin', 687902),
(7909367, 'Azithromycin', 687902),
(7932988, 'Hydrochlorothiazide', 805144),
(7949453, 'Bupropion', 790734),
(7987875, 'Levothyroxine', 803246),
(8909752, 'Montelukast', 875362),
(9846893, 'Lisinopril', 790734);

-- --------------------------------------------------------

--
-- Table structure for table `drug manufacture`
--

CREATE TABLE `drug manufacture` (
  `Company_id` int(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `City` varchar(50) NOT NULL,
  `Street` varchar(50) NOT NULL,
  `State` varchar(50) NOT NULL,
  `ZIP` int(20) NOT NULL,
  `phar_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug manufacture`
--

INSERT INTO `drug manufacture` (`Company_id`, `Name`, `City`, `Street`, `State`, `ZIP`, `phar_id`) VALUES
(687902, 'CureTech Pharmaceuticals', 'Houston', '101 Remedy Rd', 'Texas', 77001, 30125),
(790734, 'BioMed Innovations', 'Chicao', '789 Cure Ave', 'Illinois', 60601, 30124),
(803246, 'PharmaCore Inc.', 'San Francisco', '123 Health St', 'California', 94105, 30126),
(805144, 'HealthMax Laboratories', 'Newyork', '456 Wellness Blvd', 'New york', 10001, 30125),
(875362, 'NexaHealth Solutions', 'Seattle', '202 Pharma Way', 'Washington', 98101, 30126);

-- --------------------------------------------------------

--
-- Table structure for table `drug_sell`
--

CREATE TABLE `drug_sell` (
  `Drug_Id` int(20) NOT NULL,
  `Phar_id` int(20) NOT NULL COMMENT 'VARCHAR',
  `Price` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug_sell`
--

INSERT INTO `drug_sell` (`Drug_Id`, `Phar_id`, `Price`) VALUES
(5437927, 30125, '5$'),
(3180362, 30123, '10$'),
(7987875, 30123, '9$'),
(7909367, 30123, '7$'),
(9846893, 30124, '2$'),
(5437927, 30125, '3$'),
(4570912, 30124, '7$'),
(1243234, 30125, '10$'),
(8909752, 30124, '7$'),
(8909752, 30124, '8$'),
(5439872, 30123, '6$'),
(8909752, 30124, '9$'),
(1243234, 30125, '8$'),
(6909357, 30125, '4$'),
(1243234, 30126, '2$'),
(5467346, 30124, '5$'),
(5679327, 30125, '2$'),
(7909367, 30124, '7$'),
(7987875, 30125, '9$'),
(5679327, 30125, '8$');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Emp_id` int(20) NOT NULL,
  `First_name` varchar(50) NOT NULL,
  `Last_name` varchar(50) NOT NULL,
  `Phar_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Emp_id`, `First_name`, `Last_name`, `Phar_id`) VALUES
(12378, 'Twenty', 'Cent', 30123),
(40123, 'Ava', 'Clerk', 30124),
(40846, 'Ethan', 'davis', 30123),
(40874, 'Ahmed', 'thakur', 30126),
(45781, 'kanye', 'east', 30123),
(48921, 'Mahfuz', 'alam', 30123),
(50012, 'Ashraful', 'islam', 30125),
(54372, 'Manuel', 'turizo', 30125),
(65873, 'Marshal', 'Mathers', 30125),
(89732, 'Aubrey', 'graham', 30123);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `PID` int(20) NOT NULL,
  `First_name` varchar(50) NOT NULL,
  `Last_name` varchar(50) NOT NULL,
  `Sex` varchar(20) NOT NULL,
  `City` varchar(20) NOT NULL,
  `Street` varchar(50) NOT NULL,
  `State` varchar(50) NOT NULL,
  `Zip` int(50) NOT NULL,
  `Phys_ID` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`PID`, `First_name`, `Last_name`, `Sex`, `City`, `Street`, `State`, `Zip`, `Phys_ID`) VALUES
(1221, 'Shakib alom', 'Fahim', 'Male', '	Los Angeles', '	123 Maple St	', 'California', 90001, 10121),
(1234, 'Noshin ibnat', 'Adrita', 'Female', 'Chicao', '789 Oak Dr', 'Illinois', 60601, 10123),
(1321, 'Nijam uddin', 'nihon', 'Male', 'Newyork', '123 Main street', 'New york', 10001, 10122),
(1512, 'Hasibur', 'Rahim', 'Male', 'Houston', '101 Pine Ln', 'Texas', 77001, 10124);

-- --------------------------------------------------------

--
-- Table structure for table `patient_contactnumber`
--

CREATE TABLE `patient_contactnumber` (
  `PID_Contactno` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_contactnumber`
--

INSERT INTO `patient_contactnumber` (`PID_Contactno`) VALUES
('01303309173'),
('01734567890'),
('01820786543'),
('01893234567');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy`
--

CREATE TABLE `pharmacy` (
  `Phar_id` int(20) NOT NULL,
  `Primary` varchar(50) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `City` varchar(50) NOT NULL,
  `Street` varchar(50) NOT NULL,
  `State` varchar(50) NOT NULL,
  `Zip` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacy`
--

INSERT INTO `pharmacy` (`Phar_id`, `Primary`, `Type`, `City`, `Street`, `State`, `Zip`) VALUES
(30123, 'HealthPlus Pharmacy', 'Retail', '	Los Angeles', '123 Wellness Blvd', 'california', 90001),
(30124, 'CareMed Pharmacy', 'Compounding', 'Newyork', '456 Care St', 'New york', 10001),
(30125, 'Family Pharmacy', 'Retail', 'Chicao', '789 Family Ave', 'Illinois', 60601),
(30126, 'QuickMed Pharmacy', 'Compounding', 'Houston', '101 Fast Lane	', 'Texas', 77001);

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_email`
--

CREATE TABLE `pharmacy_email` (
  `Pharid_Email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacy_email`
--

INSERT INTO `pharmacy_email` (`Pharid_Email`) VALUES
('contact@healthplus.com'),
('help@quickmed.com'),
('info@caremedpharmacy.com'),
('support@familypharmacy.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`Phys_id`);

--
-- Indexes for table `drug`
--
ALTER TABLE `drug`
  ADD PRIMARY KEY (`Drug_Id`),
  ADD KEY `drug_ibfk_1` (`Company_id`);

--
-- Indexes for table `drug manufacture`
--
ALTER TABLE `drug manufacture`
  ADD PRIMARY KEY (`Company_id`),
  ADD KEY `drug manufacture_ibfk_1` (`phar_id`);

--
-- Indexes for table `drug_sell`
--
ALTER TABLE `drug_sell`
  ADD KEY `Drug_Id` (`Drug_Id`),
  ADD KEY `Phar_id` (`Phar_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Emp_id`),
  ADD KEY `employee_ibfk_1` (`Phar_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PID`),
  ADD KEY `Phys_ID` (`Phys_ID`);

--
-- Indexes for table `patient_contactnumber`
--
ALTER TABLE `patient_contactnumber`
  ADD PRIMARY KEY (`PID_Contactno`);

--
-- Indexes for table `pharmacy`
--
ALTER TABLE `pharmacy`
  ADD PRIMARY KEY (`Phar_id`);

--
-- Indexes for table `pharmacy_email`
--
ALTER TABLE `pharmacy_email`
  ADD PRIMARY KEY (`Pharid_Email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `drug`
--
ALTER TABLE `drug`
  ADD CONSTRAINT `drug_ibfk_1` FOREIGN KEY (`Company_id`) REFERENCES `drug manufacture` (`Company_id`);

--
-- Constraints for table `drug manufacture`
--
ALTER TABLE `drug manufacture`
  ADD CONSTRAINT `drug manufacture_ibfk_1` FOREIGN KEY (`phar_id`) REFERENCES `pharmacy` (`Phar_id`);

--
-- Constraints for table `drug_sell`
--
ALTER TABLE `drug_sell`
  ADD CONSTRAINT `drug_sell_ibfk_1` FOREIGN KEY (`Drug_Id`) REFERENCES `drug` (`Drug_Id`),
  ADD CONSTRAINT `drug_sell_ibfk_2` FOREIGN KEY (`Phar_id`) REFERENCES `pharmacy` (`Phar_id`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`Phar_id`) REFERENCES `pharmacy` (`Phar_id`);

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`Phys_ID`) REFERENCES `doctor` (`Phys_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
