-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2022 at 05:13 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pet_adoption_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(200) NOT NULL,
  `admin_login_id` int(200) NOT NULL,
  `admin_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_login_id`, `admin_name`) VALUES
(2, 1, 'Shirlene');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_id` int(200) NOT NULL,
  `login_username` varchar(200) NOT NULL,
  `login_password` varchar(200) NOT NULL,
  `login_rank` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`login_id`, `login_username`, `login_password`, `login_rank`) VALUES
(1, 'sysadmin', 'fe01ce2a7fbac8fafaed7c982a04e229', 'administrator'),
(2, 'N@it', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Adopter'),
(3, 'Mart', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Adopter'),
(5, 'Shirlene', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Owner'),
(7, 'Steph', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Owner'),
(8, 'Dwayne', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Adopter'),
(9, 'Lettice', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Adopter'),
(10, 'Alfonze', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Adopter'),
(11, 'Ashley', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Adopter'),
(12, 'Jojo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Adopter'),
(13, 'Daniel', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Adopter'),
(14, 'Naomi', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Adopter'),
(15, 'Eric', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Adopter');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(200) NOT NULL,
  `payment_pet_adoption_id` int(200) NOT NULL,
  `payment_ref` varchar(200) NOT NULL,
  `payment_amount` varchar(200) NOT NULL,
  `payment_date` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `payment_means` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `payment_pet_adoption_id`, `payment_ref`, `payment_amount`, `payment_date`, `payment_means`) VALUES
(5, 14, 'Q6PWJI5EAM', '500', '2022-11-09 17:03:04.876400', 'Cash'),
(7, 16, 'OADIE382SM', '500', '2022-11-09 17:03:35.745507', 'Cash'),
(8, 20, 'S8YHF7P1NX', '500', '2022-11-09 17:03:41.277254', 'Cash'),
(9, 17, 'NW6EBSLP7Z', '500', '2022-11-09 17:03:46.359000', 'Cash'),
(10, 18, 'IP4UJFN6XE', '500', '2022-11-09 17:03:52.131800', 'Cash'),
(11, 19, 'OUFB8YE0D5', '500', '2022-11-09 17:04:03.552554', 'Cash'),
(12, 21, '0738Y2SUQG', '500', '2022-11-23 17:40:34.086494', 'Cash');

-- --------------------------------------------------------

--
-- Table structure for table `pet`
--

CREATE TABLE `pet` (
  `pet_id` int(200) NOT NULL,
  `pet_owner_id` int(200) NOT NULL,
  `pet_type` varchar(200) NOT NULL,
  `pet_breed` varchar(200) NOT NULL,
  `pet_age` varchar(200) NOT NULL,
  `pet_health_status` varchar(200) NOT NULL,
  `pet_description` longtext NOT NULL,
  `pet_adoption_status` varchar(200) DEFAULT 'Pending',
  `pet_image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pet`
--

INSERT INTO `pet` (`pet_id`, `pet_owner_id`, `pet_type`, `pet_breed`, `pet_age`, `pet_health_status`, `pet_description`, `pet_adoption_status`, `pet_image`) VALUES
(9, 2, 'dog', 'puppy', '1', 'Healthy', 'Keep away from cats', 'Adopted', 'puppy.webp'),
(10, 3, 'Bird', 'parrot', '5', 'Healthy', 'Don\'t spill your secrets..shhh!', 'Adopted', 'parrot.webp'),
(11, 2, 'Dog', 'Chiwawa', '4', 'Healthy', 'Cute but feisty.', 'Adopted', 'chiwawa.jpeg'),
(12, 3, 'Hamster', 'Small hamster', '3', 'Sick', 'Pocket friendly.\r\n', 'Adopted', 'hamster.jpeg'),
(13, 2, 'Horse', 'Riding Horse', '20', 'Sick', 'Loving and fragile\r\nLoving and fragile\r\nLoving and fragile\r\nLoving and fragile\r\nLoving and fragile\r\nLoving and fragile', 'Available', 'horse.webp'),
(14, 3, 'Monkey', 'Circus Monkey', '23', 'Healthy', 'Can be a thief. Take care', 'Adopted', 'monkey.jpeg'),
(15, 3, 'Elephant', 'Elephant', '15', 'Sick', 'Baby elephant with rare disease.', 'Adopted', 'elephant.jpeg'),
(16, 2, 'Cat', 'Cat', '12', 'Healthy', 'Quite unique eyes. Glows in the dark.', 'Adopted', 'cat.jpeg'),
(17, 3, 'Lion', 'Lion', '26', 'Healthy', 'Is a carnivore', 'Available', 'lion.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `pet_adopter`
--

CREATE TABLE `pet_adopter` (
  `pet_adopter_id` int(200) NOT NULL,
  `pet_adopter_login_id` int(200) NOT NULL,
  `pet_adopter_name` varchar(200) NOT NULL,
  `pet_adopter_email` varchar(200) NOT NULL,
  `pet_adopter_phone_number` varchar(200) NOT NULL,
  `pet_adopter_address` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pet_adopter`
--

INSERT INTO `pet_adopter` (`pet_adopter_id`, `pet_adopter_login_id`, `pet_adopter_name`, `pet_adopter_email`, `pet_adopter_phone_number`, `pet_adopter_address`) VALUES
(1, 2, 'Shirlene Nait', 'shirlruwa@gmail.com', '0718279490', 'MANYANJA ROAD'),
(2, 3, 'Martin Mbithi', 'mart@gmail.com', '254718289980', 'locolhost'),
(3, 8, 'Dwayne Caleb ', 'dwayne@gmail.com', '98876545678', 'MANYANJA ROAD'),
(4, 9, 'Lettice Kawira', 'lettice@gmail.com', '098765345678', 'westlands'),
(5, 10, 'Alfonze Nzau Mwithi', 'mwithi@gmail.com', '456788876', 'Parklands'),
(6, 11, 'Ashley Benter', 'ashley@gmail.com', '7865434356', 'Imara Daima'),
(7, 12, 'Jojo Aruwa', 'jojo@gmail.com', '456789876', 'Kisumu'),
(8, 13, 'Daniel Gechu', 'daniel@gmail.com', '45678987567', 'Meru'),
(9, 14, 'Naomi Kiema', 'naomi@gmail.com', '8765567887', 'Kitui'),
(10, 15, 'Eric Muthomi', 'eric@gmail.com', '4567890423456', 'Nyali');

-- --------------------------------------------------------

--
-- Table structure for table `pet_adoption`
--

CREATE TABLE `pet_adoption` (
  `pet_adoption_id` int(200) NOT NULL,
  `pet_adoption_pet_id` int(200) NOT NULL,
  `pet_adoption_pet_adopter_id` int(200) NOT NULL,
  `pet_adoption_date` varchar(200) NOT NULL,
  `pet_adoption_payment_status` varchar(200) NOT NULL DEFAULT 'Pending',
  `pet_adoption_return_status` varchar(200) NOT NULL DEFAULT ' Not Returned ',
  `pet_adoption_ref` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pet_adoption`
--

INSERT INTO `pet_adoption` (`pet_adoption_id`, `pet_adoption_pet_id`, `pet_adoption_pet_adopter_id`, `pet_adoption_date`, `pet_adoption_payment_status`, `pet_adoption_return_status`, `pet_adoption_ref`) VALUES
(14, 9, 2, '2022-11-10', 'Paid', ' Not Returned ', 'ADP-FIPD-8326'),
(15, 10, 4, '2022-11-07', 'Pending', ' Not Returned ', 'ADP-NTHD-1394'),
(16, 11, 2, '2022-11-03', 'Paid', ' Not Returned ', 'ADP-CIVH-1094'),
(17, 12, 5, '2022-10-30', 'Paid', ' Not Returned ', 'ADP-JHKD-7219'),
(18, 13, 1, '2022-11-01', 'Paid', 'Returned', 'ADP-JEPA-2698'),
(19, 14, 6, '2022-11-12', 'Paid', ' Not Returned ', 'ADP-PGRF-1037'),
(20, 15, 6, '2022-11-03', 'Paid', ' Not Returned ', 'ADP-LSND-2035'),
(21, 16, 1, '2022-11-23', 'Paid', ' Not Returned ', 'ADP-QELH-1026');

-- --------------------------------------------------------

--
-- Table structure for table `pet_owner`
--

CREATE TABLE `pet_owner` (
  `pet_owner_id` int(200) NOT NULL,
  `pet_owner_login_id` int(200) NOT NULL,
  `pet_owner_name` varchar(200) NOT NULL,
  `pet_owner_email` varchar(200) NOT NULL,
  `pet_owner_contacts` varchar(200) NOT NULL,
  `pet_owner_address` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pet_owner`
--

INSERT INTO `pet_owner` (`pet_owner_id`, `pet_owner_login_id`, `pet_owner_name`, `pet_owner_email`, `pet_owner_contacts`, `pet_owner_address`) VALUES
(2, 5, 'Shirlene Nait', 'aruwa@gmail.com', '25479804094', 'MANYANJA ROAD'),
(3, 7, 'Dwayne', 'caleb@nbn', '0989687543657', 'MANYANJA ROAD');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `AdminLoginId` (`admin_login_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `PaymentAdoptionId` (`payment_pet_adoption_id`);

--
-- Indexes for table `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`pet_id`),
  ADD KEY `PetOwnerId` (`pet_owner_id`);

--
-- Indexes for table `pet_adopter`
--
ALTER TABLE `pet_adopter`
  ADD PRIMARY KEY (`pet_adopter_id`),
  ADD KEY `PetAdopterLoginId` (`pet_adopter_login_id`);

--
-- Indexes for table `pet_adoption`
--
ALTER TABLE `pet_adoption`
  ADD PRIMARY KEY (`pet_adoption_id`),
  ADD KEY `PetAdoptionPetId` (`pet_adoption_pet_id`),
  ADD KEY `PetAdoptionAdopterId` (`pet_adoption_pet_adopter_id`);

--
-- Indexes for table `pet_owner`
--
ALTER TABLE `pet_owner`
  ADD PRIMARY KEY (`pet_owner_id`),
  ADD KEY `PetOwnerLoginId` (`pet_owner_login_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `login_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pet`
--
ALTER TABLE `pet`
  MODIFY `pet_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pet_adopter`
--
ALTER TABLE `pet_adopter`
  MODIFY `pet_adopter_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pet_adoption`
--
ALTER TABLE `pet_adoption`
  MODIFY `pet_adoption_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pet_owner`
--
ALTER TABLE `pet_owner`
  MODIFY `pet_owner_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `AdminLoginId` FOREIGN KEY (`admin_login_id`) REFERENCES `login` (`login_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `PaymentAdoptionId` FOREIGN KEY (`payment_pet_adoption_id`) REFERENCES `pet_adoption` (`pet_adoption_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `PetOwnerId` FOREIGN KEY (`pet_owner_id`) REFERENCES `pet_owner` (`pet_owner_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pet_adopter`
--
ALTER TABLE `pet_adopter`
  ADD CONSTRAINT `PetAdopterLoginId` FOREIGN KEY (`pet_adopter_login_id`) REFERENCES `login` (`login_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pet_adoption`
--
ALTER TABLE `pet_adoption`
  ADD CONSTRAINT `PetAdoptionAdopterId` FOREIGN KEY (`pet_adoption_pet_adopter_id`) REFERENCES `pet_adopter` (`pet_adopter_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `PetAdoptionPetId` FOREIGN KEY (`pet_adoption_pet_id`) REFERENCES `pet` (`pet_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pet_owner`
--
ALTER TABLE `pet_owner`
  ADD CONSTRAINT `PetOwnerLoginId` FOREIGN KEY (`pet_owner_login_id`) REFERENCES `login` (`login_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
