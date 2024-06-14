-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jun 14, 2024 at 04:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `savoy`
--

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `banner_id` varchar(4) NOT NULL,
  `banner_poster` varchar(255) NOT NULL,
  `movie_id` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`banner_id`, `banner_poster`, `movie_id`) VALUES
('b001', 'poster1.jpg', 'm001'),
('b002', 'poster3.jpg', 'm002'),
('b003', 'poster2.jpg', 'm003');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` varchar(5) NOT NULL,
  `customer_id` varchar(5) NOT NULL,
  `showtime_id` varchar(4) NOT NULL,
  `adult` int(2) NOT NULL,
  `children` int(2) NOT NULL,
  `total` double NOT NULL,
  `parking_id` varchar(5) DEFAULT NULL,
  `seats` varchar(120) NOT NULL,
  `confirm_booking` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `customer_id`, `showtime_id`, `adult`, `children`, `total`, `parking_id`, `seats`, `confirm_booking`) VALUES
('b0002', 'c0002', 's002', 6, 4, 2250, 'p0004', 'A50,A51,A52,A53,B49,B50,B51,B52,C34,C35', 0),
('b0003', 'c0003', 's002', 4, 0, 1100, 'p0005', 'A49,A48,C7,C8', 1);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` varchar(4) NOT NULL,
  `movie_title` varchar(100) NOT NULL,
  `movie_card_poster` varchar(255) NOT NULL,
  `movie_poster` varchar(255) NOT NULL,
  `language` varchar(45) NOT NULL,
  `movie_cast` varchar(500) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `storyplot` varchar(500) NOT NULL,
  `rating` int(1) NOT NULL,
  `movie_trailer` varchar(255) NOT NULL,
  `duration` varchar(14) NOT NULL,
  `released_date` date NOT NULL,
  `current_movies` tinyint(1) NOT NULL,
  `upcoming_movies` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `movie_title`, `movie_card_poster`, `movie_poster`, `language`, `movie_cast`, `genre`, `storyplot`, `rating`, `movie_trailer`, `duration`, `released_date`, `current_movies`, `upcoming_movies`) VALUES
('m001', 'The Garfield Movie', 'garfield.jpg', 'garfield.jpg', 'English', 'Samuel L. Jackson [ACTOR],Chris Pratt [ACTOR],Ving Rhames [ACTOR],Cecily Strong [ACTOR],Nicholas Hoult [ACTOR],Hannah Waddingham [ACTOR],Mark Dindal [DIRECTOR],John Cohen [PRODUCER],Broderick Johnson [PRODUCER],Andrew Kosove [PRODUCER]', 'Adventure / Comedy / Animation', 'Garfield, the world-famous, Monday-hating, lasagna-loving indoor cat, is about to have a wild outdoor adventure. After an unexpected reunion with his long-lost father - scruffy street cat Vic- Garfield and his canine friend Odie are forced from their perfectly pampered life into joining Vic in a hilarious, high-stakes heist. ', 4, 'https://youtu.be/IeFWNtMo1Fs?si=0TFKOdzfffXKcOYs', '1hr 41mins', '2024-05-26', 1, 0),
('m002', 'Star', 'Star_Profile_Picture.jpg', 'star.jpg', 'Tamil', 'Kavin [ACTOR],Aarti Desai [ACTOR], Geetha Kailasam [ACTOR], Lal [ACTOR], Mimmo [ACTOR],Preity Mukhundhan [ACTOR],Aditi Sudhir Pohankar [ACTOR], Elan [DIRECTOR],Sagar Pentela [PRODUCER], BVSN Prasad [PRODUCER], Sreenidhi Sagar [PRODUCER]', 'Drama', 'The story unfolds as a beautiful ode to the bond between Mr. Pandian, a photographer, and his son, an actor. The father and son find solace, fulfillment, and redemption in their symbiotic relationship.', 4, 'https://youtu.be/5QlTZEogGrE?si=hMrE3toyMAVWcGmQ', '1hr 58mins', '2024-05-10', 1, 0),
('m003', 'PT Sir', 'pt_sir.jpg', 'ptSir.jpg', 'Tamil', 'Hiphop Tamizha Adhi [ACTOR],Kashmira Pardeshi [ACTOR],Munishkanth [ACTOR],Pandiarajan [ACTOR],Kashmira Pardeshi [ACTOR],Anikha Surendran [ACTOR],B. Thyagarajan [ACTOR],Karthik Venugopalan[DIRECTOR],Ishari K. Ganeshan [PRODUCER]', 'Comedy', 'It follows a young teacher as he attempts to introduce unique physical activities to school children.', 4, 'https://youtu.be/rlvJHsx6N60?si=G1-jyWL3A9Jwi8nA', '2hrs 9mins', '2024-05-24', 1, 0),
('m004', 'IF', 'if.jpg', 'if.jpg', 'English', 'John Krasinski [ACTOR], Ryan Reynolds [ACTOR], Phoebe Waller-Bridge [ACTOR], Steve Carell [ACTOR], Bobby Moynihan [ACTOR], Cailey Fleming [ACTOR], Alan S. Kim [ACTOR], Louis Gossett Jr. [ACTOR], Fiona Shaw [ACTOR], John Krasinski [DIRECTOR]', 'Comedy', 'From writer and director John Krasinski, IF is about a girl who discovers that she can see everyone’s imaginary friends — and what she does with that superpower — as she embarks on a magical adventure to reconnect forgotten IFs with their kids. IF stars Ryan Reynolds, John Krasinski, Cailey Fleming, Fiona Shaw, and the voices of Phoebe Waller-Bridge, Louis Gossett Jr. and Steve Carell alongside many more as the wonderfully unique characters that reflect the incredible power of a child’s imaginat', 5, 'https://youtu.be/mb2187ZQtBE?si=7dvmTWc5MOuKbnIq', '1hr 44mins', '2024-05-17', 1, 0),
('m005', 'Kingdom of the Planet of the Apes', 'Kingdom_of_the_Planet_of_the_Apes.jpg', 'Kingdom_of_the_Planet_of_the_Apes.jpg', 'English', 'Owen Teague [ACTOR], Freya Allan [ACTOR], Peter Macon [ACTOR], Kevin Durand [ACTOR], William H. Macy [ACTOR], Wes Ball [DIRECTOR], Wes Ball [PRODUCER], Josh Friedman [WRITER], Amanda Silver [WRITER], Rick Jaffa [WRITER]', 'Action / Sci-Fi', 'As a new tyrannical ape leader builds his empire, one young ape undertakes a harrowing journey that will cause him to question all that he has known about the past and to make choices that will define a future for apes and humans alike.', 5, 'https://youtu.be/XtFI7SNtVpY?si=OilVMF9TO8ItS65F', '2hrs 25mins', '2024-05-10', 1, 0),
('m006', 'Indian (Re-release)', 'Indian.jpg', 'Indian.jpg', 'Tamil', 'S. Shankar [DIRECTOR], S. Shankar [WRITER], Umesh Sharma [WRITER], Sujatha [WRITER], Kamal Haasan [ACTOR], Kamal Haasan [ACTOR], Sukanya [ACTOR], Sukanya [ACTOR], Manisha Koirala [ACTOR], Manisha Koirala [ACTOR], Urmila Matondkar [ACTOR], Urmila Matondkar', 'Drama / Action / Thriller', 'A series of murders take place in Chennai the only common link is that all are government servants.Inspector Krishnaswamy investigating the case finds from the writings on crime site that the killer could be more then seventy years old.His investigation leads him Senapathy who was a revolutionary and part of Subhash Chandra Bose army.Chandu his son an RTO agent is tied between two women Aishwarya and Sapna he left his family due to principles of Senapathy.The motive behind the killings of Senapa', 5, 'https://youtu.be/qPCTt-XDzdE?si=djrp1bpAbR3gIjeY', '3hrs 5mins', '2024-06-07', 1, 0),
('m007', 'The Watchers', 'The_Watchers_film_poster.jpg', 'The_Watchers.jpg', 'English', 'Dakota Fanning [ACTOR], Georgina Campbell [ACTOR], Oliver Finnegan [ACTOR], Olwen Fouere [ACTOR], Ishana Night Shyamalan [DIRECTOR], Ashwin Rajan [PRODUCER], M. Night Shyamalan [PRODUCER], Nimitt Mankad [PRODUCER], Ishana Night Shyamalan [WRITER]', 'Horror', 'Mina, a 28-year-old artist, gets stranded in an expansive, untouched forest in western Ireland. Upon finding shelter, she unknowingly becomes trapped alongside three strangers who are watched and stalked by mysterious creatures each night.', 4, 'https://youtu.be/dYo91Fq9tKY?si=JUMlWYYSYMG8R-oQ', '1hr 42mins', '2024-06-07', 1, 0),
('m008', 'Haikyu!! The Dumpster Battle', 'Haikyu_The_Dumpster_Battle.jpg', 'Haikyu_The_Dumpster_Battle.jpg', 'Japanese', 'Yuichi Nakamura [ACTOR], Yuki Kaji [ACTOR], Ayumu Murase [ACTOR], Hisao Egawa [ACTOR], Kyosuke Ikeda [ACTOR], Nobuaki Fukuda [ACTOR], Koki Uchiyama [ACTOR], Yoshimasa Hosoya [ACTOR], Satoshi Hino [ACTOR], Kaito Ishikawa [ACTOR]', 'Animation', 'Shoyo Hinata joins Karasuno High\'s volleyball club to be like his idol, a former Karasuno player known as the \'Little Giant.\' But, Hinata soon finds that he must team up with his middle school nemesis, Tobio Kageyama. Their clashing styles turn into a surprising weapon, but can they beat their rival Nekoma High in the highly anticipated \'Dumpster Battle,\' the long awaited ultimate showdown between two opposing underdog teams?', 4, 'https://youtu.be/H51vnZt1ctU?si=qGo39Ina6oRkvqrY', '1hr 25mins', '2024-05-31', 1, 0),
('m009', 'Deadpool & Wolverine', 'Deadpool_&_Wolverine_poster.jpg', 'Deadpool_&_Wolverine.jpg', 'English', 'Shawn Levy [DIRECTOR], Ryan Reynolds [ACTOR], Hugh Jackman [ACTOR], Emma Corrin [ACTOR], Morena Baccarin [ACTOR], Rob Delaney [ACTOR], Karan Soni [ACTOR], Leslie Uggams [ACTOR], Matthew Macfadyen [ACTOR]', 'ACTION', 'Wolverine is recovering from his injuries when he crosses paths with the loudmouth Deadpool. They team up to defeat a common enemy.', 4, 'https://youtu.be/73_1biulkYk?si=YITtmXgsOFwG-Jc1', '2hrs 7mins', '2024-07-25', 0, 1),
('m010', 'Despicable Me 4', 'Despicable-Me-4-trailer-shows-creation-of-Mega-Minions.jpg', 'despicable-4-me-trailer.jpg', 'English', 'Pierre Coffin [ACTOR], Steve Carell [ACTOR], Kristen Wiig [ACTOR], Steve Coogan [ACTOR], Miranda Cosgrove [ACTOR], Chris Renaud [DIRECTOR], Patrick Delage [DIRECTOR], Christopher Meledandri [PRODUCER], Mike White [WRITER]', 'ANIMATION', 'Gru, Lucy, Margo, Edith, and Agnes welcome a new member to the family, Gru Jr., who is intent on tormenting his dad. Gru faces a new nemesis in Maxime Le Mal and his girlfriend Valentina, and the family is forced to go on the run.', 5, 'https://youtu.be/qQlr9-rF32A?si=fPUfI3h2ImUQMCqf', '1hr 34mins', '2024-07-03', 0, 1),
('m011', 'Indian 2', 'indian2.jpg', 'indian2.jpg', 'Tamil', 'Shankar Shanmugham [DIRECTOR], Mark Bennington [ACTOR], Kamal Haasan [ACTOR], Kajal Aggarwal [ACTOR], Siddharth [ACTOR], Samuthirakani [ACTOR]', 'Action, Drama, Thriller', 'Senapathy, an ex-freedom fighter turned vigilante who fights against corruption. Senapathy returns to the country to aid a young man who has been exposing corrupt politicians in the country through videos on the internet.', 5, 'https://youtu.be/kqGj31bQQQ0?si=VafcnWR-Sw9_kVi2', '3hr 12 mins', '2024-07-12', 0, 1),
('m012', 'Twisters', 'twisters.jpg', 'twisters.jpg', 'English', 'Glen Powell [ACTOR], Daisy Edgar-Jones [ACTOR], Brandon Perea [ACTOR], Kiernan Shipka [ACTOR], Sasha Lane [ACTOR], Anthony Ramos [ACTOR], Nik Dodani [ACTOR], Daryl McCormack [ACTOR], Maura Tierney [ACTOR], Lee Isaac Chung [DIRECTOR]', 'ACTION', 'An update to the 1996 film \'Twister\', which centered on a pair of storm chasers who risk their lives in an attempt to test an experimental weather alert system.', 3, 'https://youtu.be/wdok0rZdmx4?si=RxJjtN0aSyDQzKT8', '2hr 2mins', '2024-07-19', 0, 1),
('m013', 'A Quiet Place: Day One', 'quite_place_one_day.jpg', 'quite_place_one_day.jpg', 'English', 'Lupita Nyong\'o [ACTOR], Joseph Quinn [ACTOR], Alex Wolff [ACTOR], Michael Sarnoski [DIRECTOR], Andrew Form [PRODUCER], Brad Fuller [PRODUCER], John Krasinski [PRODUCER], Michael Bay [PRODUCER], Michael Sarnoski [WRITER]', 'HORROR', 'A woman named Sam must survive an invasion in New York City by bloodthirsty alien creatures with ultrasonic sound hearing.', 3, 'https://youtu.be/YPY7J-flzE8?si=aPU9e5AElJVYyOYS', '1hr 40mins', '2024-06-28', 0, 1),
('m014', 'The Greatest of All Time', 'goat.jpg', 'goat.jpg', 'Tamil', 'Vijay [ACTOR], Prabhu Deva [ACTOR], Meenakshi Chaudhary [ACTOR], Sneha Prasanna [ACTOR], Prashanth Thyagarajan [ACTOR], Yogi Babu [ACTOR], Jayaram [ACTOR], Ajmal Ameer [ACTOR], Premgi Amaren [ACTOR], Vaibhav Reddy [ACTOR], Laila [ACTOR], Mohan [ACTOR], VTV Ganesh [ACTOR], Ajay Raj [ACTOR], Aravind Akash [ACTOR], Malvika Sharma [ACTOR], Raghava Lawrence [ACTOR], Venkat Prabhu [DIRECTOR & WRITER]', 'ACTION', 'An explosive thriller inspired by the 2004 Moscow Metro bombing, where a suicide bomber detonated in a crowded subway, killing dozens. It delves into the aftermath and pursuit of those responsible.', 4, 'https://youtu.be/BkfFoq_vd2k?si=3Qe8-7ow5thffWZq', 'Not Available', '2024-09-05', 0, 1),
('m015', 'Inside Out 2', 'insideout2.jpg', 'Inside-Out-2-banner.jpg', 'English', 'Amy Poehler [ACTOR], Maya Hawke [ACTOR], Ayo Edebiri [ACTOR], Grace Lu [ACTOR], Kensington Tallman [ACTOR], Lilimar [ACTOR], Sumayyah Nuriddin-Green [ACTOR], Diane Lane [ACTOR], Kyle MacLachlan [ACTOR], Lewis Black [ACTOR], Kelsey Mann [DIRECTOR], Dave Holstein [WRITER], Meg LeFauve [WRITER]', 'ANIMATION / ADVENTURE / COMEDY', ' Riley\'s emotions as they find themselves joined by new emotions that want to take over Riley\'s head', 4, 'https://youtu.be/LEjhY15eCx0?si=7eicQA_Apn28PDsp', '1hr 36mins', '2024-06-14', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `movie_feedbacks`
--

CREATE TABLE `movie_feedbacks` (
  `feedback_id` varchar(5) NOT NULL,
  `movie_id` varchar(4) NOT NULL,
  `user_id` varchar(5) NOT NULL,
  `feedbacks` varchar(999) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movie_feedbacks`
--

INSERT INTO `movie_feedbacks` (`feedback_id`, `movie_id`, `user_id`, `feedbacks`) VALUES
('f0001', 'm001', 'c0001', 'Awesome!!!'),
('f0003', 'm007', 'c0003', 'Yep! Good experience though!!!');

-- --------------------------------------------------------

--
-- Table structure for table `parking`
--

CREATE TABLE `parking` (
  `parking_id` varchar(5) NOT NULL,
  `vehicle` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parking`
--

INSERT INTO `parking` (`parking_id`, `vehicle`) VALUES
('p0001', 'Car'),
('p0002', 'Car'),
('p0003', 'Car'),
('p0004', 'Minivan'),
('p0005', 'Car'),
('p0006', 'Motorbike');

-- --------------------------------------------------------

--
-- Table structure for table `showtimes`
--

CREATE TABLE `showtimes` (
  `show_id` varchar(4) NOT NULL,
  `movie_id` varchar(4) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `showtimes`
--

INSERT INTO `showtimes` (`show_id`, `movie_id`, `date`, `time`) VALUES
('s001', 'm001', '2024-07-19', '09.00am'),
('s002', 'm001', '2024-07-19', '04.30pm'),
('s003', 'm002', '2024-07-19', '12.30pm'),
('s004', 'm001', '2024-07-19', '06.30pm'),
('s005', 'm002', '2024-07-19', '09.00pm'),
('s006', 'm001', '2024-07-20', '09.00am'),
('s007', 'm001', '2024-07-20', '04.30pm'),
('s008', 'm002', '2024-07-20', '12.30pm'),
('s009', 'm001', '2024-07-19', '06.30pm'),
('s010', 'm002', '2024-07-20', '09.00pm'),
('s011', 'm002', '2024-07-21', '12.30pm'),
('s012', 'm001', '2024-07-21', '04.30pm'),
('s013', 'm002', '2024-07-21', '06.30pm'),
('s014', 'm002', '2024-07-21', '09.00pm'),
('s015', 'm001', '2024-07-22', '09.00am'),
('s016', 'm001', '2024-07-22', '12.30pm'),
('s017', 'm002', '2024-07-22', '04.30pm'),
('s018', 'm002', '2024-07-22', '06.30pm'),
('s020', 'm003', '2024-07-23', '09.00am'),
('s021', 'm002', '2024-07-23', '12.30pm'),
('s022', 'm001', '2024-07-23', '04.30pm'),
('s023', 'm003', '2024-07-23', '06.30pm'),
('s024', 'm001', '2024-07-23', '09.00pm'),
('s025', 'm003', '2024-07-24', '09.00am'),
('s026', 'm004', '2024-07-24', '12.30pm'),
('s027', 'm002', '2024-07-24', '04.30pm'),
('s028', 'm004', '2024-07-24', '06.30pm'),
('s029', 'm002', '2024-07-24', '09.00pm'),
('s030', 'm003', '2024-07-25', '09.00am'),
('s031', 'm001', '2024-07-25', '12.30pm'),
('s032', 'm003', '2024-07-25', '04.30pm'),
('s034', 'm003', '2024-07-25', '09.00pm'),
('s035', 'm004', '2024-07-26', '09.00am'),
('s036', 'm004', '2024-07-26', '12.30pm'),
('s037', 'm004', '2024-07-26', '04.30pm'),
('s039', 'm003', '2024-07-26', '09.00pm'),
('s040', 'm004', '2024-07-27', '09.00am'),
('s042', 'm004', '2024-07-27', '04.30pm'),
('s044', 'm004', '2024-07-27', '09.00pm'),
('s046', 'm005', '2024-07-28', '12.30pm'),
('s048', 'm005', '2024-07-28', '06.30pm'),
('s049', 'm005', '2024-07-28', '09.00pm'),
('s050', 'm005', '2024-07-29', '09.00am'),
('s051', 'm004', '2024-07-29', '12.30pm'),
('s052', 'm005', '2024-07-29', '04.30pm'),
('s054', 'm004', '2024-07-29', '09.00pm'),
('s055', 'm004', '2024-07-30', '09.00am'),
('s056', 'm005', '2024-07-30', '12.30pm'),
('s057', 'm005', '2024-07-30', '04.30pm'),
('s058', 'm004', '2024-07-30', '06.30pm'),
('s059', 'm005', '2024-07-30', '09.00pm'),
('s060', 'm005', '2024-07-31', '09.00am'),
('s062', 'm003', '2024-07-31', '04.30pm'),
('s064', 'm005', '2024-07-31', '09.00pm'),
('s065', 'm005', '2024-08-01', '09.00am'),
('s066', 'm006', '2024-08-01', '12.30pm'),
('s067', 'm005', '2024-08-01', '04.30pm'),
('s068', 'm005', '2024-08-01', '06.30pm'),
('s069', 'm006', '2024-08-01', '09.00pm'),
('s070', 'm006', '2024-08-02', '09.00am'),
('s071', 'm006', '2024-08-02', '12.30pm'),
('s074', 'm006', '2024-08-02', '09.00pm'),
('s075', 'm006', '2024-08-03', '09.00am'),
('s076', 'm006', '2024-08-03', '12.30pm'),
('s079', 'm006', '2024-08-03', '09.00pm'),
('s080', 'm006', '2024-08-04', '09.00am'),
('s081', 'm007', '2024-08-04', '12.30pm'),
('s082', 'm007', '2024-08-04', '04.30pm'),
('s083', 'm006', '2024-08-04', '06.30pm'),
('s084', 'm006', '2024-08-04', '09.00pm'),
('s085', 'm007', '2024-08-05', '09.00am'),
('s086', 'm008', '2024-08-05', '12.30pm'),
('s087', 'm008', '2024-08-05', '04.30pm'),
('s088', 'm008', '2024-08-05', '06.30pm'),
('s089', 'm007', '2024-08-05', '09.00pm'),
('s090', 'm006', '2024-08-06', '09.00am'),
('s091', 'm007', '2024-08-06', '12.30pm'),
('s092', 'm008', '2024-08-06', '04.30pm'),
('s093', 'm008', '2024-08-06', '06.30pm'),
('s095', 'm008', '2024-08-07', '09.00am'),
('s096', 'm007', '2024-08-07', '12.30pm'),
('s097', 'm008', '2024-08-07', '04.30pm'),
('s099', 'm007', '2024-08-07', '09.00pm'),
('s100', 'm008', '2024-08-08', '09.00am'),
('s101', 'm008', '2024-08-08', '12.30pm'),
('s102', 'm007', '2024-08-08', '04.30pm'),
('s103', 'm007', '2024-08-08', '06.30pm'),
('s104', 'm008', '2024-08-08', '09.00pm'),
('s107', 'm008', '2024-08-09', '04.30pm'),
('s108', 'm008', '2024-08-09', '06.30pm'),
('s109', 'm008', '2024-08-09', '09.00pm');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(5) NOT NULL,
  `username` varchar(65) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `phone_no` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `user_type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `phone_no`, `email`, `user_type`) VALUES
('c0001', 'keerthi', '$2y$10$50yEbAqggTy3oabel1OareeRAG60592VXtotgeXmK2510yRxRzIX2', 'Keerthika', 'Jeyandran', '+94711145410', 'kichukee56@gmail.com', 0),
('c0002', 'dhaksith', '$2y$10$yVDyrW7tg9vl3r59HblP9.Nz/0GpflaiFMVuC53bTM47hFvVdfXmu', 'Sanjeevan', 'Dhaksithan', '+94770475254', 'dhaksithan5@gmail.com', 2),
('c0003', 'rishi', '$2y$10$clieVeHd8AwRzqdXhG2Kp.o6t5XJhOhUnFLbOMOwzcUYtRYA1TjiC', 'Vijayanath', 'Rishinath', '+94776096158', 'rnath0764146264@gmail.com', 2),
('s0004', 'kichu', '$2y$10$wFChljvSlAoqMDlGyMR7IerTKrcImirOSw5xGugf9WZoHhoq9h81a', 'Kichu', 'Jeyan', '+94776094785', 'kichukee56@gmail.com', 1),
('s0005', 'thashan5', '$2y$10$WyscyvsTNmBeNb3chgCTkO8M5u4CV8.5DC2Rwq3spuEKL.6NkDFDa', 'Jeya', 'Thashan', '+94776058459', 'jblog1997@gmail.com', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`banner_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `showtime_id` (`showtime_id`),
  ADD KEY `parking_id` (`parking_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `movie_feedbacks`
--
ALTER TABLE `movie_feedbacks`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `parking`
--
ALTER TABLE `parking`
  ADD PRIMARY KEY (`parking_id`);

--
-- Indexes for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD PRIMARY KEY (`show_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banner`
--
ALTER TABLE `banner`
  ADD CONSTRAINT `banner_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`show_id`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`parking_id`) REFERENCES `parking` (`parking_id`);

--
-- Constraints for table `movie_feedbacks`
--
ALTER TABLE `movie_feedbacks`
  ADD CONSTRAINT `movie_feedbacks_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`),
  ADD CONSTRAINT `movie_feedbacks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD CONSTRAINT `showtimes_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
