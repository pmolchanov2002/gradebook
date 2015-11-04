-- MySQL dump 10.13  Distrib 5.6.24, for osx10.8 (x86_64)
--
-- Host: 127.0.0.1    Database: glukin_gradebook2
-- ------------------------------------------------------
-- Server version	5.5.5-10.0.21-MariaDB

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
-- Table structure for table `Address`
--

DROP TABLE IF EXISTS `Address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Address` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Street` varchar(50) NOT NULL,
  `Street1` varchar(50) NOT NULL,
  `Zip` varchar(10) NOT NULL,
  `StateId` int(20) DEFAULT NULL,
  `CountryId` int(20) DEFAULT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `CountryId` (`CountryId`),
  UNIQUE KEY `StateId` (`StateId`),
  CONSTRAINT `idx_countryid_country_id` FOREIGN KEY (`CountryId`) REFERENCES `Country` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_stateid_state_id` FOREIGN KEY (`StateId`) REFERENCES `State` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Address`
--

LOCK TABLES `Address` WRITE;
/*!40000 ALTER TABLE `Address` DISABLE KEYS */;
/*!40000 ALTER TABLE `Address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Assessment`
--

DROP TABLE IF EXISTS `Assessment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Assessment` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Assessment`
--

LOCK TABLES `Assessment` WRITE;
/*!40000 ALTER TABLE `Assessment` DISABLE KEYS */;
/*!40000 ALTER TABLE `Assessment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Attendance`
--

DROP TABLE IF EXISTS `Attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Attendance` (
  `LessonId` int(11) NOT NULL,
  `StudentId` int(11) NOT NULL,
  `TeacherId` int(11) NOT NULL,
  `DayId` int(11) NOT NULL,
  `Present` tinyint(4) DEFAULT NULL,
  `Created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`LessonId`,`StudentId`,`TeacherId`,`DayId`),
  KEY `idx_studentid_attendance_idx` (`StudentId`),
  KEY `idx_teacherid_attendance_idx` (`TeacherId`),
  KEY `idx_dayid_attendance_idx` (`DayId`),
  CONSTRAINT `idx_dayid_attendance` FOREIGN KEY (`DayId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_lessonid_attendance` FOREIGN KEY (`LessonId`) REFERENCES `Lesson` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_studentid_attendance` FOREIGN KEY (`StudentId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_teacherid_attendance` FOREIGN KEY (`TeacherId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Attendance`
--

LOCK TABLES `Attendance` WRITE;
/*!40000 ALTER TABLE `Attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `Attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Class`
--

DROP TABLE IF EXISTS `Class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Class` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Ordinal` int(11) NOT NULL,
  `YearId` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `NameYear` (`Name`,`YearId`) USING BTREE,
  KEY `Name` (`Name`),
  KEY `YearId` (`YearId`),
  CONSTRAINT `idx_year3` FOREIGN KEY (`YearId`) REFERENCES `Year` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Class`
--

LOCK TABLES `Class` WRITE;
/*!40000 ALTER TABLE `Class` DISABLE KEYS */;
/*!40000 ALTER TABLE `Class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Class_Student`
--

DROP TABLE IF EXISTS `Class_Student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Class_Student` (
  `ClassId` int(11) NOT NULL,
  `StudentId` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `ClassId` (`ClassId`,`StudentId`),
  KEY `StudentId` (`StudentId`),
  CONSTRAINT `idx_classid_class_id` FOREIGN KEY (`ClassId`) REFERENCES `Class` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_studentid_student_id` FOREIGN KEY (`StudentId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Class_Student`
--

LOCK TABLES `Class_Student` WRITE;
/*!40000 ALTER TABLE `Class_Student` DISABLE KEYS */;
/*!40000 ALTER TABLE `Class_Student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Country`
--

DROP TABLE IF EXISTS `Country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Id`),
  KEY `Name` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Country`
--

LOCK TABLES `Country` WRITE;
/*!40000 ALTER TABLE `Country` DISABLE KEYS */;
/*!40000 ALTER TABLE `Country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Course`
--

DROP TABLE IF EXISTS `Course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Course` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `CourseName` (`Name`),
  KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Course`
--

LOCK TABLES `Course` WRITE;
/*!40000 ALTER TABLE `Course` DISABLE KEYS */;
/*!40000 ALTER TABLE `Course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Day`
--

DROP TABLE IF EXISTS `Day`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `Date` date NOT NULL,
  `YearId` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `YearId` (`YearId`),
  CONSTRAINT `idx_yearid_day` FOREIGN KEY (`YearId`) REFERENCES `Year` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Day`
--

LOCK TABLES `Day` WRITE;
/*!40000 ALTER TABLE `Day` DISABLE KEYS */;
/*!40000 ALTER TABLE `Day` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Exam`
--

DROP TABLE IF EXISTS `Exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ExamTypeId` int(11) NOT NULL,
  `YearId` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Ordinal` int(11) DEFAULT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ExamName` (`Name`),
  KEY `idx_examtype_exam_idx` (`ExamTypeId`),
  KEY `idx_year_exam_idx` (`YearId`),
  CONSTRAINT `idx_examtype_exam` FOREIGN KEY (`ExamTypeId`) REFERENCES `ExamType` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_year_exam` FOREIGN KEY (`YearId`) REFERENCES `Year` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Exam`
--

LOCK TABLES `Exam` WRITE;
/*!40000 ALTER TABLE `Exam` DISABLE KEYS */;
/*!40000 ALTER TABLE `Exam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ExamType`
--

DROP TABLE IF EXISTS `ExamType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ExamType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Code` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ExamTypeName` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ExamType`
--

LOCK TABLES `ExamType` WRITE;
/*!40000 ALTER TABLE `ExamType` DISABLE KEYS */;
INSERT INTO `ExamType` VALUES (1,'Intermediate','MidTerm'),(2,'Final','Final');
/*!40000 ALTER TABLE `ExamType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GradeAttendance`
--

DROP TABLE IF EXISTS `GradeAttendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GradeAttendance` (
  `ExamId` int(11) NOT NULL,
  `YearId` int(11) NOT NULL,
  `StudentId` int(11) NOT NULL,
  `Grade` double NOT NULL,
  `MaxGrade` double NOT NULL,
  PRIMARY KEY (`ExamId`,`YearId`,`StudentId`),
  KEY `idx_gradeattendance_year_idx` (`YearId`),
  KEY `idx_gradeattendance_student_idx` (`StudentId`),
  CONSTRAINT `idx_gradeaatendance_exam` FOREIGN KEY (`ExamId`) REFERENCES `Exam` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_gradeattendance_student` FOREIGN KEY (`StudentId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_gradeattendance_year` FOREIGN KEY (`YearId`) REFERENCES `Year` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `GradeAttendance`
--

LOCK TABLES `GradeAttendance` WRITE;
/*!40000 ALTER TABLE `GradeAttendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `GradeAttendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GradeExam`
--

DROP TABLE IF EXISTS `GradeExam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GradeExam` (
  `ExamId` int(11) NOT NULL,
  `StudentId` int(11) NOT NULL,
  `CourseId` int(11) NOT NULL,
  `ClassId` int(11) NOT NULL,
  `TeacherId` int(11) NOT NULL,
  `GradeTypeId` int(11) NOT NULL,
  `Grade` int(11) DEFAULT NULL,
  `Created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ExamId`,`StudentId`,`CourseId`,`ClassId`,`GradeTypeId`),
  KEY `idx_studentid_gradeexam_idx` (`StudentId`),
  KEY `idx_teacherid_gradeexam_idx` (`TeacherId`),
  KEY `idx_classid_gradeexam_idx` (`ClassId`),
  KEY `idx_gradeid_gradeexam_idx` (`GradeTypeId`),
  CONSTRAINT `idx_classid_gradeexam` FOREIGN KEY (`ClassId`) REFERENCES `Class` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_examid_gradeexam` FOREIGN KEY (`ExamId`) REFERENCES `Exam` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_gradeid_gradeexam` FOREIGN KEY (`GradeTypeId`) REFERENCES `GradeType` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_studentid_gradeexam` FOREIGN KEY (`StudentId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_teacherid_gradeexam` FOREIGN KEY (`TeacherId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `GradeExam`
--

LOCK TABLES `GradeExam` WRITE;
/*!40000 ALTER TABLE `GradeExam` DISABLE KEYS */;
/*!40000 ALTER TABLE `GradeExam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GradeLesson`
--

DROP TABLE IF EXISTS `GradeLesson`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GradeLesson` (
  `LessonId` int(11) NOT NULL,
  `StudentId` int(11) NOT NULL,
  `TeacherId` int(11) NOT NULL,
  `AssessmentId` int(11) NOT NULL,
  `DayId` int(11) NOT NULL,
  `Grade` int(11) NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`LessonId`,`StudentId`,`TeacherId`,`AssessmentId`,`DayId`),
  KEY `idx_studentid_gradelesson_idx` (`StudentId`),
  KEY `idx_teacherid_gradelesson_idx` (`TeacherId`),
  KEY `idx_assessmentid_gradelesson_idx` (`AssessmentId`),
  KEY `idx_dayid_gradelesson_idx` (`DayId`),
  CONSTRAINT `idx_assessmentid_gradelesson` FOREIGN KEY (`AssessmentId`) REFERENCES `Assessment` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_dayid_gradelesson` FOREIGN KEY (`DayId`) REFERENCES `Day` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_lessonid_gradelesson` FOREIGN KEY (`LessonId`) REFERENCES `Lesson` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_studentid_gradelesson` FOREIGN KEY (`StudentId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_teacherid_gradelesson` FOREIGN KEY (`TeacherId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `GradeLesson`
--

LOCK TABLES `GradeLesson` WRITE;
/*!40000 ALTER TABLE `GradeLesson` DISABLE KEYS */;
/*!40000 ALTER TABLE `GradeLesson` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GradeType`
--

DROP TABLE IF EXISTS `GradeType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GradeType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Code` varchar(45) NOT NULL,
  `Ordinal` int(11) NOT NULL,
  `Algorithm` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `GradeType`
--

LOCK TABLES `GradeType` WRITE;
/*!40000 ALTER TABLE `GradeType` DISABLE KEYS */;
INSERT INTO `GradeType` VALUES (1,'Предмет','Course',1,'PLAIN'),(2,'Прилежание','Diligence',2,'AVG'),(3,'Поведение','Discipline',3,'AVG');
/*!40000 ALTER TABLE `GradeType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Lesson`
--

DROP TABLE IF EXISTS `Lesson`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Lesson` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `CourseId` int(11) NOT NULL,
  `TeacherId` int(11) NOT NULL,
  `ClassId` int(11) NOT NULL,
  `PeriodId` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `idx_class_period` (`ClassId`,`PeriodId`),
  UNIQUE KEY `idx_class_course_period` (`CourseId`,`ClassId`,`PeriodId`),
  UNIQUE KEY `idx_class_course_period_teacher` (`PeriodId`,`ClassId`,`TeacherId`,`CourseId`),
  KEY `ClassId` (`ClassId`),
  KEY `TeacherId` (`TeacherId`),
  KEY `CourseId` (`CourseId`),
  KEY `idx_periodid_idx` (`PeriodId`),
  CONSTRAINT `idx_classid` FOREIGN KEY (`ClassId`) REFERENCES `Class` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_courseid_course_id` FOREIGN KEY (`CourseId`) REFERENCES `Course` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_periodid` FOREIGN KEY (`PeriodId`) REFERENCES `Period` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_teacherid_teacher_userid` FOREIGN KEY (`TeacherId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Lesson`
--

LOCK TABLES `Lesson` WRITE;
/*!40000 ALTER TABLE `Lesson` DISABLE KEYS */;
/*!40000 ALTER TABLE `Lesson` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Period`
--

DROP TABLE IF EXISTS `Period`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Period` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Ordinal` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `PeriodName` (`Name`),
  KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Period`
--

LOCK TABLES `Period` WRITE;
/*!40000 ALTER TABLE `Period` DISABLE KEYS */;
/*!40000 ALTER TABLE `Period` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Phone`
--

DROP TABLE IF EXISTS `Phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Phone` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Phone` varchar(50) NOT NULL,
  `PhoneTypeId` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Id`),
  KEY `PhoneTypeId` (`PhoneTypeId`),
  CONSTRAINT `idx_phonetypeid_phonetype_id` FOREIGN KEY (`PhoneTypeId`) REFERENCES `PhoneType` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Phone`
--

LOCK TABLES `Phone` WRITE;
/*!40000 ALTER TABLE `Phone` DISABLE KEYS */;
/*!40000 ALTER TABLE `Phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PhoneType`
--

DROP TABLE IF EXISTS `PhoneType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PhoneType` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `PhoneTypeName` (`Name`),
  KEY `Name` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PhoneType`
--

LOCK TABLES `PhoneType` WRITE;
/*!40000 ALTER TABLE `PhoneType` DISABLE KEYS */;
/*!40000 ALTER TABLE `PhoneType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Role`
--

DROP TABLE IF EXISTS `Role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Role` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `RoleName` (`Name`),
  UNIQUE KEY `RoleRole` (`Role`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Role`
--

LOCK TABLES `Role` WRITE;
/*!40000 ALTER TABLE `Role` DISABLE KEYS */;
INSERT INTO `Role` VALUES (1,'Admin','ROLE_ADMIN'),(2,'Teacher','ROLE_TEACHER'),(3,'Student','ROLE_STUDENT'),(4,'Parent','ROLE_PARENT');
/*!40000 ALTER TABLE `Role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `State`
--

DROP TABLE IF EXISTS `State`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `State` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `State`
--

LOCK TABLES `State` WRITE;
/*!40000 ALTER TABLE `State` DISABLE KEYS */;
/*!40000 ALTER TABLE `State` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`Id`),
  UNIQUE KEY `AddressId` (`AddressId`),
  KEY `Login` (`Username`,`FirstName`,`LastName`),
  CONSTRAINT `idx_addressid_user` FOREIGN KEY (`AddressId`) REFERENCES `Address` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,'admin','202cb962ac59075b964b07152d234b70','Admin','Admin','1972-01-01','admin@gmail.com',NULL,NULL,NULL,'2015-09-12 20:45:38',1);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User_Phone`
--

DROP TABLE IF EXISTS `User_Phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User_Phone` (
  `UserId` int(11) NOT NULL,
  `PhoneId` int(11) NOT NULL,
  KEY `UserId` (`UserId`,`PhoneId`),
  KEY `PhoneId` (`PhoneId`),
  CONSTRAINT `idx_phoneid_phone_id` FOREIGN KEY (`PhoneId`) REFERENCES `Phone` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_userid_user_id` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User_Phone`
--

LOCK TABLES `User_Phone` WRITE;
/*!40000 ALTER TABLE `User_Phone` DISABLE KEYS */;
/*!40000 ALTER TABLE `User_Phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User_Role`
--

DROP TABLE IF EXISTS `User_Role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User_Role` (
  `userId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  PRIMARY KEY (`userId`,`roleId`),
  KEY `idx_roleid_userrole_idx` (`roleId`),
  CONSTRAINT `idx_roleid_userrole` FOREIGN KEY (`roleId`) REFERENCES `Role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_userid_userrole` FOREIGN KEY (`userId`) REFERENCES `User` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User_Role`
--

LOCK TABLES `User_Role` WRITE;
/*!40000 ALTER TABLE `User_Role` DISABLE KEYS */;
INSERT INTO `User_Role` VALUES (1,1);
/*!40000 ALTER TABLE `User_Role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Year`
--

DROP TABLE IF EXISTS `Year`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Year` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Year`
--

LOCK TABLES `Year` WRITE;
/*!40000 ALTER TABLE `Year` DISABLE KEYS */;
/*!40000 ALTER TABLE `Year` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-03 21:41:26
