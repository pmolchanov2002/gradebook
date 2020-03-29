-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 28, 2020 at 08:10 PM
-- Server version: 5.6.41-84.1
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `glukin_gradebook2`
--
CREATE DATABASE IF NOT EXISTS `glukin_gradebook2` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `glukin_gradebook2`;

-- --------------------------------------------------------

--
-- Table structure for table `Address`
--

DROP TABLE IF EXISTS `Address`;
CREATE TABLE `Address` (
  `Id` int(11) NOT NULL,
  `Street` varchar(50) NOT NULL,
  `Street1` varchar(50) NOT NULL,
  `Zip` varchar(10) NOT NULL,
  `StateId` int(20) DEFAULT NULL,
  `CountryId` int(20) DEFAULT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Article`
--

DROP TABLE IF EXISTS `Article`;
CREATE TABLE `Article` (
  `Id` int(11) NOT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Description` varchar(2048) DEFAULT NULL,
  `Lang` varchar(10) DEFAULT NULL,
  `Content` mediumblob,
  `Status` varchar(10) DEFAULT NULL,
  `TypeId` int(11) DEFAULT NULL,
  `AuthorId` int(11) DEFAULT NULL,
  `Created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `IconId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ArticleType`
--

DROP TABLE IF EXISTS `ArticleType`;
CREATE TABLE `ArticleType` (
  `Id` int(11) NOT NULL,
  `Name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ArticleType`
--

INSERT INTO `ArticleType` (`Id`, `Name`) VALUES
(1, 'General article (read-only)');

-- --------------------------------------------------------

--
-- Table structure for table `Article_Document`
--

DROP TABLE IF EXISTS `Article_Document`;
CREATE TABLE `Article_Document` (
  `ArticleId` int(11) NOT NULL,
  `MediaId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Article_Image`
--

DROP TABLE IF EXISTS `Article_Image`;
CREATE TABLE `Article_Image` (
  `ArticleId` int(11) NOT NULL,
  `MediaId` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Article_Video`
--

DROP TABLE IF EXISTS `Article_Video`;
CREATE TABLE `Article_Video` (
  `ArticleId` int(11) NOT NULL,
  `MediaId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Assessment`
--

DROP TABLE IF EXISTS `Assessment`;
CREATE TABLE `Assessment` (
  `Id` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Attendance`
--

DROP TABLE IF EXISTS `Attendance`;
CREATE TABLE `Attendance` (
  `LessonId` int(11) NOT NULL,
  `StudentId` int(11) NOT NULL,
  `TeacherId` int(11) NOT NULL,
  `DayId` int(11) NOT NULL,
  `Present` tinyint(4) DEFAULT NULL,
  `Created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Banner`
--

DROP TABLE IF EXISTS `Banner`;
CREATE TABLE `Banner` (
  `Id` int(11) NOT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `Description` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Banner_Media`
--

DROP TABLE IF EXISTS `Banner_Media`;
CREATE TABLE `Banner_Media` (
  `BannerId` int(11) NOT NULL,
  `MediaId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Class`
--

DROP TABLE IF EXISTS `Class`;
CREATE TABLE `Class` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Ordinal` int(11) NOT NULL,
  `YearId` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `EnglishName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Class_Student`
--

DROP TABLE IF EXISTS `Class_Student`;
CREATE TABLE `Class_Student` (
  `ClassId` int(11) NOT NULL,
  `StudentId` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Country`
--

DROP TABLE IF EXISTS `Country`;
CREATE TABLE `Country` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Course`
--

DROP TABLE IF EXISTS `Course`;
CREATE TABLE `Course` (
  `Id` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `EnglishName` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Day`
--

DROP TABLE IF EXISTS `Day`;
CREATE TABLE `Day` (
  `id` int(11) NOT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `Date` date NOT NULL,
  `YearId` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Event`
--

DROP TABLE IF EXISTS `Event`;
CREATE TABLE `Event` (
  `Id` int(11) NOT NULL,
  `EventTypeId` int(11) NOT NULL DEFAULT '0',
  `YearId` int(11) NOT NULL DEFAULT '0',
  `EventDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `IconId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `EventLabel`
--

DROP TABLE IF EXISTS `EventLabel`;
CREATE TABLE `EventLabel` (
  `EventId` int(11) NOT NULL DEFAULT '0',
  `Title` varchar(255) DEFAULT NULL,
  `Description` text,
  `Lang` varchar(10) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `EventType`
--

DROP TABLE IF EXISTS `EventType`;
CREATE TABLE `EventType` (
  `Id` int(11) NOT NULL,
  `EnTitle` varchar(255) NOT NULL DEFAULT '',
  `EnDescription` varchar(8192) DEFAULT NULL,
  `RuTitle` varchar(255) NOT NULL DEFAULT '',
  `RuDescription` varchar(8192) DEFAULT NULL,
  `Ordinal` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `EventType_Role`
--

DROP TABLE IF EXISTS `EventType_Role`;
CREATE TABLE `EventType_Role` (
  `EventTypeId` int(11) NOT NULL DEFAULT '0',
  `RoleId` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Event_Article`
--

DROP TABLE IF EXISTS `Event_Article`;
CREATE TABLE `Event_Article` (
  `EventId` int(11) NOT NULL DEFAULT '0',
  `ArticleId` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Event_Media`
--

DROP TABLE IF EXISTS `Event_Media`;
CREATE TABLE `Event_Media` (
  `EventId` int(11) NOT NULL DEFAULT '0',
  `MediaId` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Exam`
--

DROP TABLE IF EXISTS `Exam`;
CREATE TABLE `Exam` (
  `id` int(11) NOT NULL,
  `ExamTypeId` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Ordinal` int(11) DEFAULT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ExamType`
--

DROP TABLE IF EXISTS `ExamType`;
CREATE TABLE `ExamType` (
  `id` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Code` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ExamType`
--

INSERT INTO `ExamType` (`id`, `Name`, `Code`) VALUES
(1, 'Четверть', 'MidTerm'),
(2, 'Экзамен', 'Exam');

-- --------------------------------------------------------

--
-- Table structure for table `ExamWeight`
--

DROP TABLE IF EXISTS `ExamWeight`;
CREATE TABLE `ExamWeight` (
  `ExamTypeId` int(11) NOT NULL,
  `GradeTypeId` int(11) NOT NULL,
  `Weight` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `FinalAverages`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `FinalAverages`;
CREATE TABLE `FinalAverages` (
`name` varchar(50)
,`firstName` varchar(50)
,`lastName` varchar(50)
,`s` int(11)
,`c` int(11)
,`ordinal` int(11)
,`grade` double
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `FinalGrades`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `FinalGrades`;
CREATE TABLE `FinalGrades` (
`name` varchar(50)
,`firstName` varchar(50)
,`lastName` varchar(50)
,`s` int(11)
,`c` int(11)
,`grade` double
,`ordinal` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `FinalGradesAndAttendance`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `FinalGradesAndAttendance`;
CREATE TABLE `FinalGradesAndAttendance` (
`name` varchar(50)
,`firstName` varchar(50)
,`lastName` varchar(50)
,`grade` double
,`attendanceGrade` double
,`attendanceMaxGrade` double
,`attendancePercentage` double(17,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `GradeAttendance`
--

DROP TABLE IF EXISTS `GradeAttendance`;
CREATE TABLE `GradeAttendance` (
  `ExamId` int(11) NOT NULL,
  `YearId` int(11) NOT NULL,
  `StudentId` int(11) NOT NULL,
  `Grade` double NOT NULL,
  `MaxGrade` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `GradeExam`
--

DROP TABLE IF EXISTS `GradeExam`;
CREATE TABLE `GradeExam` (
  `ExamId` int(11) NOT NULL,
  `StudentId` int(11) NOT NULL,
  `CourseId` int(11) NOT NULL,
  `ClassId` int(11) NOT NULL,
  `TeacherId` int(11) NOT NULL,
  `GradeTypeId` int(11) NOT NULL,
  `Grade` int(11) DEFAULT NULL,
  `Created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `GradeLesson`
--

DROP TABLE IF EXISTS `GradeLesson`;
CREATE TABLE `GradeLesson` (
  `LessonId` int(11) NOT NULL,
  `StudentId` int(11) NOT NULL,
  `TeacherId` int(11) NOT NULL,
  `AssessmentId` int(11) NOT NULL,
  `DayId` int(11) NOT NULL,
  `Grade` int(11) NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `GradeType`
--

DROP TABLE IF EXISTS `GradeType`;
CREATE TABLE `GradeType` (
  `id` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Code` varchar(45) NOT NULL,
  `Ordinal` int(11) NOT NULL,
  `Algorithm` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Language`
--

DROP TABLE IF EXISTS `Language`;
CREATE TABLE `Language` (
  `Code` varchar(10) NOT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `NativeName` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Language`
--

INSERT INTO `Language` (`Code`, `Name`, `NativeName`) VALUES
('en', 'English', 'English'),
('ru', 'Russian', 'Русский');

-- --------------------------------------------------------

--
-- Table structure for table `Lesson`
--

DROP TABLE IF EXISTS `Lesson`;
CREATE TABLE `Lesson` (
  `Id` int(11) NOT NULL,
  `CourseId` int(11) NOT NULL,
  `TeacherId` int(11) NOT NULL,
  `ClassId` int(11) NOT NULL,
  `PeriodId` int(11) NOT NULL,
  `MeetingLink` varchar(2048) DEFAULT NULL,
  `MeetingPassword` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Media`
--

DROP TABLE IF EXISTS `Media`;
CREATE TABLE `Media` (
  `Id` int(11) NOT NULL,
  `Description` varchar(2048) DEFAULT NULL,
  `TypeId` int(11) DEFAULT NULL,
  `Path` varchar(2048) NOT NULL,
  `Width` int(11) DEFAULT NULL,
  `Height` int(11) DEFAULT NULL,
  `EnglishName` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `MediaType`
--

DROP TABLE IF EXISTS `MediaType`;
CREATE TABLE `MediaType` (
  `Id` int(11) NOT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `MimeType` varchar(255) DEFAULT NULL,
  `IconClass` varchar(45) DEFAULT NULL,
  `IconPath` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `MediaType`
--

INSERT INTO `MediaType` (`Id`, `Name`, `MimeType`, `IconClass`, `IconPath`) VALUES
(1, 'Jpeg', 'image/jpeg', NULL, NULL),
(2, 'PDF', 'application/pdf', 'fa-file-pdf-o', NULL),
(3, 'DOCX', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'fa-file-word-o ', NULL),
(4, 'XLSX', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'fa-file-excel-o', NULL),
(5, 'Video', 'video/mp4', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Menu`
--

DROP TABLE IF EXISTS `Menu`;
CREATE TABLE `Menu` (
  `Id` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `SubTitle` varchar(255) DEFAULT NULL,
  `ParentMenuId` int(11) DEFAULT NULL,
  `MenuTypeId` int(11) NOT NULL,
  `Lang` varchar(10) NOT NULL,
  `Status` varchar(10) NOT NULL,
  `SortOrder` int(11) NOT NULL,
  `PageId` varchar(45) DEFAULT NULL,
  `Url` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `MenuType`
--

DROP TABLE IF EXISTS `MenuType`;
CREATE TABLE `MenuType` (
  `Id` int(11) NOT NULL,
  `Name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `MenuType`
--

INSERT INTO `MenuType` (`Id`, `Name`) VALUES
(1, 'Page'),
(3, 'External page'),
(4, 'Grouping'),
(5, 'Contacts');

-- --------------------------------------------------------

--
-- Table structure for table `Page`
--

DROP TABLE IF EXISTS `Page`;
CREATE TABLE `Page` (
  `Id` varchar(45) NOT NULL,
  `Title` varchar(45) DEFAULT NULL,
  `SubTitle` varchar(255) DEFAULT NULL,
  `PageTypeId` int(11) DEFAULT NULL,
  `Lang` varchar(10) NOT NULL,
  `ArticlesPerPage` int(11) DEFAULT '1',
  `BannerId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `PageType`
--

DROP TABLE IF EXISTS `PageType`;
CREATE TABLE `PageType` (
  `Id` int(11) NOT NULL,
  `Name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PageType`
--

INSERT INTO `PageType` (`Id`, `Name`) VALUES
(1, 'Single Article'),
(2, 'Lenta'),
(3, 'Contacts'),
(4, 'Lessons'),
(5, 'Results');

-- --------------------------------------------------------

--
-- Table structure for table `Page_Article`
--

DROP TABLE IF EXISTS `Page_Article`;
CREATE TABLE `Page_Article` (
  `PageId` varchar(45) NOT NULL,
  `ArticleId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Page_User`
--

DROP TABLE IF EXISTS `Page_User`;
CREATE TABLE `Page_User` (
  `PageId` varchar(45) NOT NULL,
  `UserId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Period`
--

DROP TABLE IF EXISTS `Period`;
CREATE TABLE `Period` (
  `Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Ordinal` int(11) NOT NULL,
  `EnglishName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Phone`
--

DROP TABLE IF EXISTS `Phone`;
CREATE TABLE `Phone` (
  `Id` int(11) NOT NULL,
  `Phone` varchar(50) NOT NULL,
  `PhoneTypeId` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `PhoneType`
--

DROP TABLE IF EXISTS `PhoneType`;
CREATE TABLE `PhoneType` (
  `Id` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Role`
--

DROP TABLE IF EXISTS `Role`;
CREATE TABLE `Role` (
  `id` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Role` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `State`
--

DROP TABLE IF EXISTS `State`;
CREATE TABLE `State` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Status`
--

DROP TABLE IF EXISTS `Status`;
CREATE TABLE `Status` (
  `Code` varchar(10) NOT NULL,
  `Name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Status`
--

INSERT INTO `Status` (`Code`, `Name`) VALUES
('DRAFT', 'Draft'),
('PREVIEW', 'Preview'),
('PUBLISHED', 'Published');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `Id` int(10) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `DOB` date DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `MobilePhone` varchar(45) DEFAULT NULL,
  `HomePhone` varchar(45) DEFAULT NULL,
  `AddressId` int(10) DEFAULT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Active` tinyint(4) NOT NULL,
  `EnglishName` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `User_Parent`
--

DROP TABLE IF EXISTS `User_Parent`;
CREATE TABLE `User_Parent` (
  `UserId` int(11) NOT NULL,
  `ParentId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `User_Phone`
--

DROP TABLE IF EXISTS `User_Phone`;
CREATE TABLE `User_Phone` (
  `UserId` int(11) NOT NULL,
  `PhoneId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `User_Role`
--

DROP TABLE IF EXISTS `User_Role`;
CREATE TABLE `User_Role` (
  `userId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Year`
--

DROP TABLE IF EXISTS `Year`;
CREATE TABLE `Year` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Year`
--

INSERT INTO `Year` (`Id`, `Name`, `Updated`, `Created`, `Active`) VALUES
(20, '2015/2016', '2015-10-04 00:02:37', '2015-10-04 00:02:37', 0),
(21, '2016/2017', '2016-08-31 01:44:23', '2016-08-31 01:44:23', 0),
(22, '2017/2018', '2017-07-31 00:36:58', '2017-07-31 00:36:58', 0),
(23, '2018/2019', '2018-07-04 19:09:41', '2018-07-04 19:09:41', 0),
(24, '2019/2020', '2019-08-17 12:36:47', '2019-08-17 12:36:47', 1);

-- --------------------------------------------------------

--
-- Structure for view `FinalAverages`
--
DROP TABLE IF EXISTS `FinalAverages`;

CREATE ALGORITHM=UNDEFINED DEFINER=`glukin`@`localhost` SQL SECURITY DEFINER VIEW `FinalAverages`  AS  select `c`.`Name` AS `name`,`u`.`FirstName` AS `firstName`,`u`.`LastName` AS `lastName`,`ge`.`StudentId` AS `s`,`c`.`Id` AS `c`,`c`.`Ordinal` AS `ordinal`,(avg(`ge`.`Grade`) * `we`.`Weight`) AS `grade` from (((((`GradeExam` `ge` join `Exam` `e` on((`ge`.`ExamId` = `e`.`id`))) join `User` `u` on((`ge`.`StudentId` = `u`.`Id`))) join `Class` `c` on((`ge`.`ClassId` = `c`.`Id`))) join `Year` `y` on((`c`.`YearId` = `y`.`Id`))) join `ExamWeight` `we` on(((`we`.`ExamTypeId` = `e`.`ExamTypeId`) and (`we`.`GradeTypeId` = `ge`.`GradeTypeId`)))) where (`y`.`Active` = 1) group by `ge`.`StudentId`,`ge`.`GradeTypeId`,`e`.`ExamTypeId` ;

-- --------------------------------------------------------

--
-- Structure for view `FinalGrades`
--
DROP TABLE IF EXISTS `FinalGrades`;

CREATE ALGORITHM=UNDEFINED DEFINER=`glukin`@`localhost` SQL SECURITY DEFINER VIEW `FinalGrades`  AS  select `gr`.`name` AS `name`,`gr`.`firstName` AS `firstName`,`gr`.`lastName` AS `lastName`,`gr`.`s` AS `s`,`gr`.`c` AS `c`,sum(`gr`.`grade`) AS `grade`,`gr`.`ordinal` AS `ordinal` from `FinalAverages` `gr` group by `gr`.`s`,`gr`.`c` order by `gr`.`ordinal`,sum(`gr`.`grade`) desc ;

-- --------------------------------------------------------

--
-- Structure for view `FinalGradesAndAttendance`
--
DROP TABLE IF EXISTS `FinalGradesAndAttendance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`glukin`@`localhost` SQL SECURITY DEFINER VIEW `FinalGradesAndAttendance`  AS  select `fg`.`name` AS `name`,`fg`.`firstName` AS `firstName`,`fg`.`lastName` AS `lastName`,`fg`.`grade` AS `grade`,sum(`ga`.`Grade`) AS `attendanceGrade`,sum(`ga`.`MaxGrade`) AS `attendanceMaxGrade`,round(((sum(`ga`.`Grade`) / sum(`ga`.`MaxGrade`)) * 100),0) AS `attendancePercentage` from ((`FinalGrades` `fg` join `GradeAttendance` `ga` on((`ga`.`StudentId` = `fg`.`s`))) join `Year` `y` on((`y`.`Id` = `ga`.`YearId`))) where (`y`.`Active` = 1) group by `fg`.`s` order by `fg`.`ordinal`,`fg`.`grade` desc,sum(`ga`.`Grade`) desc ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Address`
--
ALTER TABLE `Address`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `CountryId` (`CountryId`),
  ADD UNIQUE KEY `StateId` (`StateId`);

--
-- Indexes for table `Article`
--
ALTER TABLE `Article`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `article_title` (`Title`),
  ADD KEY `article_languagecode_idx` (`Lang`),
  ADD KEY `article_status_idx` (`Status`),
  ADD KEY `article_articletypeid_idx` (`TypeId`),
  ADD KEY `article_iconid_idx` (`IconId`);

--
-- Indexes for table `ArticleType`
--
ALTER TABLE `ArticleType`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Article_Document`
--
ALTER TABLE `Article_Document`
  ADD PRIMARY KEY (`ArticleId`,`MediaId`);

--
-- Indexes for table `Article_Image`
--
ALTER TABLE `Article_Image`
  ADD PRIMARY KEY (`ArticleId`,`MediaId`),
  ADD KEY `articleicon_mediaid_idx` (`MediaId`);

--
-- Indexes for table `Article_Video`
--
ALTER TABLE `Article_Video`
  ADD PRIMARY KEY (`ArticleId`,`MediaId`);

--
-- Indexes for table `Assessment`
--
ALTER TABLE `Assessment`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Attendance`
--
ALTER TABLE `Attendance`
  ADD PRIMARY KEY (`LessonId`,`StudentId`,`TeacherId`,`DayId`),
  ADD KEY `idx_studentid_attendance_idx` (`StudentId`),
  ADD KEY `idx_teacherid_attendance_idx` (`TeacherId`),
  ADD KEY `idx_dayid_attendance_idx` (`DayId`);

--
-- Indexes for table `Banner`
--
ALTER TABLE `Banner`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Banner_Media`
--
ALTER TABLE `Banner_Media`
  ADD PRIMARY KEY (`BannerId`,`MediaId`),
  ADD KEY `bannermedia_mediaid_idx` (`MediaId`);

--
-- Indexes for table `Class`
--
ALTER TABLE `Class`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `NameYear` (`Name`,`YearId`) USING BTREE,
  ADD KEY `Name` (`Name`),
  ADD KEY `YearId` (`YearId`);

--
-- Indexes for table `Class_Student`
--
ALTER TABLE `Class_Student`
  ADD KEY `ClassId` (`ClassId`,`StudentId`),
  ADD KEY `StudentId` (`StudentId`);

--
-- Indexes for table `Country`
--
ALTER TABLE `Country`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Name` (`Name`);

--
-- Indexes for table `Course`
--
ALTER TABLE `Course`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `CourseName` (`Name`),
  ADD KEY `Name` (`Name`);

--
-- Indexes for table `Day`
--
ALTER TABLE `Day`
  ADD PRIMARY KEY (`id`),
  ADD KEY `YearId` (`YearId`);

--
-- Indexes for table `Event`
--
ALTER TABLE `Event`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_event_year` (`YearId`),
  ADD KEY `fx_event_eventtype` (`EventTypeId`),
  ADD KEY `idx_event_datetime` (`EventDate`) USING BTREE,
  ADD KEY `fk_event_icon` (`IconId`);

--
-- Indexes for table `EventLabel`
--
ALTER TABLE `EventLabel`
  ADD PRIMARY KEY (`EventId`),
  ADD KEY `fk_eventlabel_lang` (`Lang`);

--
-- Indexes for table `EventType`
--
ALTER TABLE `EventType`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_eventtype_title` (`EnTitle`) USING BTREE;

--
-- Indexes for table `EventType_Role`
--
ALTER TABLE `EventType_Role`
  ADD PRIMARY KEY (`EventTypeId`,`RoleId`),
  ADD KEY `fk_eventtyperole_role` (`RoleId`);

--
-- Indexes for table `Event_Article`
--
ALTER TABLE `Event_Article`
  ADD PRIMARY KEY (`EventId`,`ArticleId`),
  ADD KEY `idx_eventarticle_event` (`EventId`) USING BTREE,
  ADD KEY `fx_eventarticle_article` (`ArticleId`);

--
-- Indexes for table `Event_Media`
--
ALTER TABLE `Event_Media`
  ADD PRIMARY KEY (`EventId`,`MediaId`),
  ADD KEY `idx_eventmedia_event` (`EventId`) USING BTREE,
  ADD KEY `fk_eventmedia_media` (`MediaId`);

--
-- Indexes for table `Exam`
--
ALTER TABLE `Exam`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ExamName` (`Name`),
  ADD KEY `idx_examtype_exam_idx` (`ExamTypeId`);

--
-- Indexes for table `ExamType`
--
ALTER TABLE `ExamType`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ExamTypeName` (`Name`);

--
-- Indexes for table `ExamWeight`
--
ALTER TABLE `ExamWeight`
  ADD PRIMARY KEY (`ExamTypeId`,`GradeTypeId`),
  ADD KEY `idx_gradetype_examweight_idx` (`GradeTypeId`);

--
-- Indexes for table `GradeAttendance`
--
ALTER TABLE `GradeAttendance`
  ADD PRIMARY KEY (`ExamId`,`YearId`,`StudentId`),
  ADD KEY `idx_gradeattendance_year_idx` (`YearId`),
  ADD KEY `idx_gradeattendance_student_idx` (`StudentId`);

--
-- Indexes for table `GradeExam`
--
ALTER TABLE `GradeExam`
  ADD PRIMARY KEY (`ExamId`,`StudentId`,`CourseId`,`ClassId`,`GradeTypeId`),
  ADD KEY `idx_studentid_gradeexam_idx` (`StudentId`),
  ADD KEY `idx_teacherid_gradeexam_idx` (`TeacherId`),
  ADD KEY `idx_classid_gradeexam_idx` (`ClassId`),
  ADD KEY `idx_gradeid_gradeexam_idx` (`GradeTypeId`);

--
-- Indexes for table `GradeLesson`
--
ALTER TABLE `GradeLesson`
  ADD PRIMARY KEY (`LessonId`,`StudentId`,`TeacherId`,`AssessmentId`,`DayId`),
  ADD KEY `idx_studentid_gradelesson_idx` (`StudentId`),
  ADD KEY `idx_teacherid_gradelesson_idx` (`TeacherId`),
  ADD KEY `idx_assessmentid_gradelesson_idx` (`AssessmentId`),
  ADD KEY `idx_dayid_gradelesson_idx` (`DayId`);

--
-- Indexes for table `GradeType`
--
ALTER TABLE `GradeType`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Language`
--
ALTER TABLE `Language`
  ADD PRIMARY KEY (`Code`);

--
-- Indexes for table `Lesson`
--
ALTER TABLE `Lesson`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `idx_class_period` (`ClassId`,`PeriodId`),
  ADD UNIQUE KEY `idx_class_course_period` (`CourseId`,`ClassId`,`PeriodId`),
  ADD UNIQUE KEY `idx_class_course_period_teacher` (`PeriodId`,`ClassId`,`TeacherId`,`CourseId`),
  ADD KEY `ClassId` (`ClassId`),
  ADD KEY `TeacherId` (`TeacherId`),
  ADD KEY `CourseId` (`CourseId`),
  ADD KEY `idx_periodid_idx` (`PeriodId`);

--
-- Indexes for table `Media`
--
ALTER TABLE `Media`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `media_mediatypeid_idx` (`TypeId`);

--
-- Indexes for table `MediaType`
--
ALTER TABLE `MediaType`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Menu`
--
ALTER TABLE `Menu`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `index_title` (`Title`),
  ADD KEY `menu_menuid_idx` (`ParentMenuId`),
  ADD KEY `menu_languagecode_idx` (`Lang`),
  ADD KEY `menu_menutypeid_idx` (`MenuTypeId`),
  ADD KEY `menu_status_idx` (`Status`),
  ADD KEY `menu_pageid_idx` (`PageId`);

--
-- Indexes for table `MenuType`
--
ALTER TABLE `MenuType`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Page`
--
ALTER TABLE `Page`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `page_languagecode_idx` (`Lang`),
  ADD KEY `page_bannerid_idx` (`BannerId`);

--
-- Indexes for table `PageType`
--
ALTER TABLE `PageType`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Page_Article`
--
ALTER TABLE `Page_Article`
  ADD PRIMARY KEY (`PageId`,`ArticleId`),
  ADD KEY `pagearticle_articleid_idx` (`ArticleId`);

--
-- Indexes for table `Page_User`
--
ALTER TABLE `Page_User`
  ADD PRIMARY KEY (`PageId`,`UserId`),
  ADD KEY `pageuser_userid_idx` (`UserId`);

--
-- Indexes for table `Period`
--
ALTER TABLE `Period`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `PeriodName` (`Name`),
  ADD KEY `Name` (`Name`);

--
-- Indexes for table `Phone`
--
ALTER TABLE `Phone`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `PhoneTypeId` (`PhoneTypeId`);

--
-- Indexes for table `PhoneType`
--
ALTER TABLE `PhoneType`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `PhoneTypeName` (`Name`),
  ADD KEY `Name` (`Name`);

--
-- Indexes for table `Role`
--
ALTER TABLE `Role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `RoleName` (`Name`),
  ADD UNIQUE KEY `RoleRole` (`Role`);

--
-- Indexes for table `State`
--
ALTER TABLE `State`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `Status`
--
ALTER TABLE `Status`
  ADD PRIMARY KEY (`Code`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `AddressId` (`AddressId`),
  ADD KEY `Login` (`Username`,`FirstName`,`LastName`);

--
-- Indexes for table `User_Parent`
--
ALTER TABLE `User_Parent`
  ADD PRIMARY KEY (`UserId`,`ParentId`),
  ADD KEY `idx_parentid_userparent_idx` (`ParentId`);

--
-- Indexes for table `User_Phone`
--
ALTER TABLE `User_Phone`
  ADD KEY `UserId` (`UserId`,`PhoneId`),
  ADD KEY `PhoneId` (`PhoneId`);

--
-- Indexes for table `User_Role`
--
ALTER TABLE `User_Role`
  ADD PRIMARY KEY (`userId`,`roleId`),
  ADD KEY `idx_roleid_userrole_idx` (`roleId`);

--
-- Indexes for table `Year`
--
ALTER TABLE `Year`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Address`
--
ALTER TABLE `Address`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Article`
--
ALTER TABLE `Article`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `Assessment`
--
ALTER TABLE `Assessment`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Banner`
--
ALTER TABLE `Banner`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `Class`
--
ALTER TABLE `Class`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `Country`
--
ALTER TABLE `Country`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Course`
--
ALTER TABLE `Course`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `Day`
--
ALTER TABLE `Day`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Event`
--
ALTER TABLE `Event`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `EventType`
--
ALTER TABLE `EventType`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `Exam`
--
ALTER TABLE `Exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ExamType`
--
ALTER TABLE `ExamType`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `GradeType`
--
ALTER TABLE `GradeType`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Lesson`
--
ALTER TABLE `Lesson`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=350;

--
-- AUTO_INCREMENT for table `Media`
--
ALTER TABLE `Media`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=470;

--
-- AUTO_INCREMENT for table `Menu`
--
ALTER TABLE `Menu`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `Period`
--
ALTER TABLE `Period`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Phone`
--
ALTER TABLE `Phone`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PhoneType`
--
ALTER TABLE `PhoneType`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Role`
--
ALTER TABLE `Role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `State`
--
ALTER TABLE `State`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=318;

--
-- AUTO_INCREMENT for table `Year`
--
ALTER TABLE `Year`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Address`
--
ALTER TABLE `Address`
  ADD CONSTRAINT `idx_countryid_country_id` FOREIGN KEY (`CountryId`) REFERENCES `Country` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_stateid_state_id` FOREIGN KEY (`StateId`) REFERENCES `State` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Article`
--
ALTER TABLE `Article`
  ADD CONSTRAINT `article_articletypeid` FOREIGN KEY (`TypeId`) REFERENCES `ArticleType` (`Id`),
  ADD CONSTRAINT `article_iconid` FOREIGN KEY (`IconId`) REFERENCES `Media` (`Id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `article_languagecode` FOREIGN KEY (`Lang`) REFERENCES `Language` (`Code`),
  ADD CONSTRAINT `article_status` FOREIGN KEY (`Status`) REFERENCES `Status` (`Code`);

--
-- Constraints for table `Attendance`
--
ALTER TABLE `Attendance`
  ADD CONSTRAINT `idx_dayid_attendance` FOREIGN KEY (`DayId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_lessonid_attendance` FOREIGN KEY (`LessonId`) REFERENCES `Lesson` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_studentid_attendance` FOREIGN KEY (`StudentId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_teacherid_attendance` FOREIGN KEY (`TeacherId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Banner_Media`
--
ALTER TABLE `Banner_Media`
  ADD CONSTRAINT `bannermedia_bannerid` FOREIGN KEY (`BannerId`) REFERENCES `Banner` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `bannermedia_mediaid` FOREIGN KEY (`MediaId`) REFERENCES `Media` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Class`
--
ALTER TABLE `Class`
  ADD CONSTRAINT `idx_year3` FOREIGN KEY (`YearId`) REFERENCES `Year` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Class_Student`
--
ALTER TABLE `Class_Student`
  ADD CONSTRAINT `idx_classid_class_id` FOREIGN KEY (`ClassId`) REFERENCES `Class` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_studentid_student_id` FOREIGN KEY (`StudentId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Day`
--
ALTER TABLE `Day`
  ADD CONSTRAINT `idx_yearid_day` FOREIGN KEY (`YearId`) REFERENCES `Year` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Event`
--
ALTER TABLE `Event`
  ADD CONSTRAINT `fk_event_icon` FOREIGN KEY (`IconId`) REFERENCES `Media` (`Id`),
  ADD CONSTRAINT `fk_event_year` FOREIGN KEY (`YearId`) REFERENCES `Year` (`Id`),
  ADD CONSTRAINT `fx_event_eventtype` FOREIGN KEY (`EventTypeId`) REFERENCES `EventType` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `EventLabel`
--
ALTER TABLE `EventLabel`
  ADD CONSTRAINT `fk_eventlabel_event` FOREIGN KEY (`EventId`) REFERENCES `Event` (`Id`),
  ADD CONSTRAINT `fk_eventlabel_lang` FOREIGN KEY (`Lang`) REFERENCES `Language` (`Code`);

--
-- Constraints for table `EventType_Role`
--
ALTER TABLE `EventType_Role`
  ADD CONSTRAINT `fk_eventtyperole_eventtype` FOREIGN KEY (`EventTypeId`) REFERENCES `EventType` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_eventtyperole_role` FOREIGN KEY (`RoleId`) REFERENCES `Role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Event_Article`
--
ALTER TABLE `Event_Article`
  ADD CONSTRAINT `fx_eventarticle_article` FOREIGN KEY (`ArticleId`) REFERENCES `Article` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fx_eventarticle_event` FOREIGN KEY (`EventId`) REFERENCES `Event` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Event_Media`
--
ALTER TABLE `Event_Media`
  ADD CONSTRAINT `fk_eventmedia_event` FOREIGN KEY (`EventId`) REFERENCES `Event` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_eventmedia_media` FOREIGN KEY (`MediaId`) REFERENCES `Media` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Exam`
--
ALTER TABLE `Exam`
  ADD CONSTRAINT `idx_examtype_exam` FOREIGN KEY (`ExamTypeId`) REFERENCES `ExamType` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ExamWeight`
--
ALTER TABLE `ExamWeight`
  ADD CONSTRAINT `idx_examtype_examweight` FOREIGN KEY (`ExamTypeId`) REFERENCES `ExamType` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `idx_gradetype_examweight` FOREIGN KEY (`GradeTypeId`) REFERENCES `GradeType` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `GradeAttendance`
--
ALTER TABLE `GradeAttendance`
  ADD CONSTRAINT `idx_gradeaatendance_exam` FOREIGN KEY (`ExamId`) REFERENCES `Exam` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_gradeattendance_student` FOREIGN KEY (`StudentId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_gradeattendance_year` FOREIGN KEY (`YearId`) REFERENCES `Year` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `GradeExam`
--
ALTER TABLE `GradeExam`
  ADD CONSTRAINT `idx_classid_gradeexam` FOREIGN KEY (`ClassId`) REFERENCES `Class` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_examid_gradeexam` FOREIGN KEY (`ExamId`) REFERENCES `Exam` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_gradeid_gradeexam` FOREIGN KEY (`GradeTypeId`) REFERENCES `GradeType` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_studentid_gradeexam` FOREIGN KEY (`StudentId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_teacherid_gradeexam` FOREIGN KEY (`TeacherId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `GradeLesson`
--
ALTER TABLE `GradeLesson`
  ADD CONSTRAINT `idx_assessmentid_gradelesson` FOREIGN KEY (`AssessmentId`) REFERENCES `Assessment` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_dayid_gradelesson` FOREIGN KEY (`DayId`) REFERENCES `Day` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_lessonid_gradelesson` FOREIGN KEY (`LessonId`) REFERENCES `Lesson` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_studentid_gradelesson` FOREIGN KEY (`StudentId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_teacherid_gradelesson` FOREIGN KEY (`TeacherId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Lesson`
--
ALTER TABLE `Lesson`
  ADD CONSTRAINT `idx_classid` FOREIGN KEY (`ClassId`) REFERENCES `Class` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_courseid_course_id` FOREIGN KEY (`CourseId`) REFERENCES `Course` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_periodid` FOREIGN KEY (`PeriodId`) REFERENCES `Period` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_teacherid_teacher_userid` FOREIGN KEY (`TeacherId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Media`
--
ALTER TABLE `Media`
  ADD CONSTRAINT `media_mediatypeid` FOREIGN KEY (`TypeId`) REFERENCES `MediaType` (`Id`);

--
-- Constraints for table `Menu`
--
ALTER TABLE `Menu`
  ADD CONSTRAINT `menu_languagecode` FOREIGN KEY (`Lang`) REFERENCES `Language` (`Code`),
  ADD CONSTRAINT `menu_menuid` FOREIGN KEY (`ParentMenuId`) REFERENCES `Menu` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `menu_menutypeid` FOREIGN KEY (`MenuTypeId`) REFERENCES `MenuType` (`Id`),
  ADD CONSTRAINT `menu_pageid` FOREIGN KEY (`PageId`) REFERENCES `Page` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `menu_status` FOREIGN KEY (`Status`) REFERENCES `Status` (`Code`);

--
-- Constraints for table `Page`
--
ALTER TABLE `Page`
  ADD CONSTRAINT `page_bannerid` FOREIGN KEY (`BannerId`) REFERENCES `Banner` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `page_languagecode` FOREIGN KEY (`Lang`) REFERENCES `Language` (`Code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Page_Article`
--
ALTER TABLE `Page_Article`
  ADD CONSTRAINT `pagearticle_articleid` FOREIGN KEY (`ArticleId`) REFERENCES `Article` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pagearticle_pageid` FOREIGN KEY (`PageId`) REFERENCES `Page` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Page_User`
--
ALTER TABLE `Page_User`
  ADD CONSTRAINT `pageuser_pageid` FOREIGN KEY (`PageId`) REFERENCES `Page` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pageuser_userid` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Phone`
--
ALTER TABLE `Phone`
  ADD CONSTRAINT `idx_phonetypeid_phonetype_id` FOREIGN KEY (`PhoneTypeId`) REFERENCES `PhoneType` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `idx_addressid_user` FOREIGN KEY (`AddressId`) REFERENCES `Address` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `User_Parent`
--
ALTER TABLE `User_Parent`
  ADD CONSTRAINT `idx_parentid_userparent` FOREIGN KEY (`ParentId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_userid_userparent` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `User_Phone`
--
ALTER TABLE `User_Phone`
  ADD CONSTRAINT `idx_phoneid_phone_id` FOREIGN KEY (`PhoneId`) REFERENCES `Phone` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_userid_user_id` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `User_Role`
--
ALTER TABLE `User_Role`
  ADD CONSTRAINT `idx_roleid_userrole` FOREIGN KEY (`roleId`) REFERENCES `Role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idx_userid_userrole` FOREIGN KEY (`userId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
