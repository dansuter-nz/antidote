-- MySQL dump 10.13  Distrib 8.0.20, for Linux (x86_64)
--
-- Host: localhost    Database: adds
-- ------------------------------------------------------
-- Server version	8.0.20-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `budget_figures`
--

DROP TABLE IF EXISTS `budget_figures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `budget_figures` (
  `Department` text,
  `Vote` text,
  `AppID` int DEFAULT NULL,
  `ParentID` int DEFAULT NULL,
  `AppropriationName` text,
  `CategoryName` text,
  `Group_Type` text,
  `Appropriation_or_Category_Type` text,
  `Restriction_Type` varchar(250) DEFAULT NULL,
  `Functional_Classification` text,
  `Amount` int DEFAULT NULL,
  `Year` int DEFAULT NULL,
  `Amount_Type` text,
  `Periodicity` text,
  `Current_Scope` text,
  `M_Number` int DEFAULT NULL,
  `Portfolio_Name` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id_comment` int NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `id_department` int DEFAULT NULL,
  `id_category` int DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_edited` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_person` int NOT NULL,
  `year` int NOT NULL,
  PRIMARY KEY (`id_comment`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id_department` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id_department`),
  UNIQUE KEY `id_department_UNIQUE` (`id_department`)
) ENGINE=InnoDB AUTO_INCREMENT=8231 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `departments_category`
--

DROP TABLE IF EXISTS `departments_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments_category` (
  `id_category` int NOT NULL AUTO_INCREMENT,
  `id_department` int NOT NULL,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id_category`),
  UNIQUE KEY `id_category_UNIQUE` (`id_category`)
) ENGINE=InnoDB AUTO_INCREMENT=13956 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `people` (
  `id_person` int NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `id_father` int NOT NULL,
  `id_mother` int NOT NULL,
  `Citizen_rating` decimal(10,0) NOT NULL,
  `Fiat_Balance` decimal(10,0) NOT NULL,
  `Crypto_Balance` decimal(12,0) DEFAULT NULL,
  PRIMARY KEY (`id_person`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='People of the world this DB is for you and all the other sientient creatures.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solution_answers`
--

DROP TABLE IF EXISTS `solution_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solution_answers` (
  `id_solution_answer` int NOT NULL AUTO_INCREMENT,
  `id_solution` int DEFAULT NULL,
  `answer` text,
  `date_added` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_person` int DEFAULT NULL,
  PRIMARY KEY (`id_solution_answer`),
  UNIQUE KEY `id_solution_answer_UNIQUE` (`id_solution_answer`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solutions`
--

DROP TABLE IF EXISTS `solutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solutions` (
  `id_solution` int NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  `intro` varchar(4096) NOT NULL,
  `Description` text NOT NULL,
  PRIMARY KEY (`id_solution`),
  UNIQUE KEY `id_solution_UNIQUE` (`id_solution`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stats` (
  `comments` int NOT NULL,
  `pos_votes` int NOT NULL,
  `neg_votes` int NOT NULL,
  `total_votes` int NOT NULL,
  `updated` datetime NOT NULL,
  `articles` int NOT NULL,
  `net_votes` int DEFAULT NULL,
  PRIMARY KEY (`comments`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stuff_articles`
--

DROP TABLE IF EXISTS `stuff_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stuff_articles` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `streamID` varchar(80) NOT NULL,
  `streamTitle` varchar(255) DEFAULT NULL,
  `status` varchar(8) DEFAULT NULL,
  `streamURL` varchar(1024) DEFAULT NULL,
  `categoryID` varchar(30) NOT NULL,
  `createdate` bigint NOT NULL,
  `date_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commentCount` smallint DEFAULT '0',
  `threadCount` smallint DEFAULT '0',
  `ratingCount` smallint DEFAULT '0',
  `rssURL` varchar(1024) DEFAULT NULL,
  `isUserSubscribed` tinyint DEFAULT NULL,
  `vote_count` int NOT NULL DEFAULT '0',
  `has_comments` tinyint DEFAULT '0',
  `antidote_comment` text,
  `category_name` varchar(100) DEFAULT NULL,
  `id_stuff` varchar(20) DEFAULT NULL,
  `category_2_name` varchar(100) DEFAULT NULL,
  `category_3_name` varchar(100) DEFAULT NULL,
  `has_img` bit(1) DEFAULT (0),
  PRIMARY KEY (`ID`),
  UNIQUE KEY `idx_streamid` (`streamID`)
) ENGINE=InnoDB AUTO_INCREMENT=139938 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stuff_categories`
--

DROP TABLE IF EXISTS `stuff_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stuff_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(50) DEFAULT NULL,
  `cat_link` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stuff_comments`
--

DROP TABLE IF EXISTS `stuff_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stuff_comments` (
  `ID` varchar(32) NOT NULL,
  `streamId` varchar(80) NOT NULL,
  `parentID` varchar(32) DEFAULT NULL,
  `threadID` varchar(32) DEFAULT NULL,
  `isModerator` varchar(32) DEFAULT NULL,
  `commentText` varchar(5000) DEFAULT NULL,
  `TotalVotes` smallint DEFAULT NULL,
  `posVotes` smallint DEFAULT NULL,
  `negVotes` smallint DEFAULT NULL,
  `vote` varchar(10) DEFAULT NULL,
  `sender` varchar(100) DEFAULT NULL,
  `photoURL` varchar(1024) DEFAULT NULL,
  `profileURL` varchar(1024) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `isSelf` tinyint(1) DEFAULT NULL,
  `loginProvider` varchar(20) DEFAULT NULL,
  `flagCount` tinyint DEFAULT NULL,
  `replies` tinyint(1) DEFAULT NULL,
  `highlightGroups` varchar(8000) DEFAULT NULL,
  `edited` tinyint(1) DEFAULT NULL,
  `timestamp` varchar(50) DEFAULT NULL,
  `threadTimestamp` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_streamid` (`streamId`),
  KEY `idx_name` (`name`),
  KEY `idx_id` (`ID`),
  CONSTRAINT `fk_article` FOREIGN KEY (`streamId`) REFERENCES `stuff_articles` (`streamid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `votes` (
  `id_vote` int NOT NULL AUTO_INCREMENT,
  `id_person` int DEFAULT NULL,
  `id_comment` int DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vote_up` bit(1) NOT NULL,
  `vote_down` bit(1) NOT NULL,
  PRIMARY KEY (`id_vote`),
  UNIQUE KEY `id_vote_UNIQUE` (`id_vote`),
  UNIQUE KEY `idx_vote_restrict` (`id_person`,`id_comment`,`vote_up`),
  UNIQUE KEY `idx_vote_restrict_2` (`id_person`,`id_comment`,`vote_down`),
  KEY `idx_comment_idx` (`id_comment`),
  KEY `idx_person_id_idx` (`id_person`),
  CONSTRAINT `idx_comment_id` FOREIGN KEY (`id_comment`) REFERENCES `comments` (`id_comment`),
  CONSTRAINT `idx_person_id` FOREIGN KEY (`id_person`) REFERENCES `antidote`.`people` (`id_people`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'adds'
--

--
-- Dumping routines for database 'adds'
--
/*!50003 DROP PROCEDURE IF EXISTS `getCategorySpendingbyID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getCategorySpendingbyID`(in category int, in iyear int)
BEGIN
SELECT department,CategoryName, Portfolio_Name,sum(amount) 'total_budget' ,
Current_Scope,Functional_Classification,amount_type,Restriction_Type, group_type, AppropriationName, Appropriation_or_Category_Type,
d.id_department,dc.id_category,
(Select count(*) from comments c3 where c3.id_category=dc.id_category) 'Comments',
(Select count(*) from votes v inner join comments c2 on c2.id_comment=v.id_comment where c2.id_category=dc.id_category) 'Votes'
FROM adds.budget_figures b
inner join departments d
on d.name=b.department
inner join departments_category dc on 
dc.name=b.categoryname and dc.id_department=d.id_department

Where b.year=iyear 
and dc.id_category=category
group by department, CategoryName, Portfolio_Name,
Current_Scope,Functional_Classification,amount_type,Restriction_Type, group_type, AppropriationName, Appropriation_or_Category_Type
order by sum(amount) desc ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getCommentsByCategoryID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getCommentsByCategoryID`(in category int)
BEGIN
SELECT c.id_comment, c.text,
(SELECT CONCAT(
FLOOR(HOUR(TIMEDIFF(now(), date_added)) / 24), ' days ',
MOD(HOUR(TIMEDIFF(now(), date_added)), 24), ' hours ',
MINUTE(TIMEDIFF(now(), date_added)), ' minutes')) 'date_added'
,p.name,
(Select sum(vote_up)-sum(vote_down) from votes v where c.id_comment=v.id_comment) 'Votes', p.image_path,p.id_people
FROM adds.comments c
inner join departments d on 
c.id_department=d.id_department
inner join departments_category dc on 
dc.id_category=c.id_category and dc.id_department=c.id_department
inner join antidote.people p on
p.id_people=c.id_person
where dc.id_category=category
order by (Select sum(vote_up)-sum(vote_down) from votes v where c.id_comment=v.id_comment) desc;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getDepartmentSpendingbyCategory` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getDepartmentSpendingbyCategory`(in dept varchar(100),in ord varchar(50),iyear int)
BEGIN

SELECT department,CategoryName, Portfolio_Name,sum(amount) 'total_budget' ,
Current_Scope,Functional_Classification,amount_type,Restriction_Type, group_type, AppropriationName, Appropriation_or_Category_Type,
d.id_department,dc.id_category,
(Select count(*) from comments c3 where c3.id_category=dc.id_category) 'Comments',
(Select count(*) from votes v inner join comments c2 on c2.id_comment=v.id_comment where c2.id_category=dc.id_category) 'Votes'
FROM adds.budget_figures b
inner join departments d
on d.name=b.department
inner join departments_category dc on 
dc.name=b.categoryname and dc.id_department=d.id_department
Where b.year=iyear 
and department=Dept
group by department, CategoryName, Portfolio_Name,
Current_Scope,Functional_Classification,amount_type,Restriction_Type, group_type, AppropriationName, Appropriation_or_Category_Type,
d.id_department,dc.id_category
order by sum(amount) desc ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_rank_person_article` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_rank_person_article`(myname varchar(100),article_id varchar(100),tv int)
BEGIN
SELECT myRank from 
(
Select name,TotalVotes,RANK() OVER (
        ORDER BY TotalVotes desc
    ) myRank 
from stuff_comments x 
where x.streamId=article_id
) b  
where b.name=myname and TotalVotes=tv
order by myRank
limit 1;
                        
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Get_results_by_ID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get_results_by_ID`(id int,viewRows int)
BEGIN
SELECT 	sa.streamTitle,
      commentText
      ,streamURL
      ,sa.commentCount
      ,sa.vote_count
      ,sc.TotalVotes
      ,posVotes
      ,negVotes
      ,name
      ,replies
      ,sa.antidote_comment
      ,has_img,
      sa.id
  FROM stuff_articles sa 
  left join adds.stuff_comments sc on sc.streamId=sa.streamID
  where sa.id=id
  order by TotalVotes desc
  limit viewRows;
			
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_results_by_name` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_results_by_name`(myname varchar(100),viewRows int)
BEGIN
SELECT 
	sa.ID,
	sa.streamTitle,
    sc.streamID,
      commentText
      ,streamURL
      ,sa.commentCount
      ,sa.vote_count
      ,sc.TotalVotes
      ,posVotes
      ,negVotes
      ,name
      ,replies
      ,sa.antidote_comment
      ,(Select COUNT(ID) from stuff_comments s1 where s1.name=myname) 'Total_Comments'
      ,(Select SUM(TotalVotes) from stuff_comments s1 where s1.name=myname) 'Total_votes'
      ,(Select SUM(PosVotes) from stuff_comments s1 where s1.name=myname) 'Total_posVotes'
      ,(Select SUM(negVotes) from stuff_comments s1 where s1.name=myname) 'Total_negVotes'
      ,(Select count(name) from (Select s1.name,count(*) 'comments' from stuff_comments s1 group by s1.name) a) 'Total_people'
      ,(SELECT b.oRank from 
			(Select name,sum(TotalVotes) 'tvotes',rank() OVER w AS 'oRank' 
			from stuff_comments x 
            group by name 
			WINDOW w AS (ORDER BY sum(TotalVotes) desc)
			) b  
			where b.name=myname) 'Overall_Rank'
  FROM   adds.stuff_comments sc
  inner join stuff_articles sa 
	on sc.streamId=sa.streamID
  where sc.name=myname
group by
	sa.ID,
	sc.streamId,
	sa.streamTitle,
      commentText
      ,streamURL
      ,sa.commentCount
      ,sa.vote_count
      ,sc.TotalVotes
      ,posVotes
      ,negVotes
      ,name
      ,replies
      ,sa.antidote_comment
  order by TotalVotes desc
  limit 100;
			
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `P_Ins_Comment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `P_Ins_Comment`(in txt text,in cat int,in person int,in yr int,in e int)
BEGIN
#check to see if there is a comment with same text from this user
if e=0 then
	if (Select count(*) from comments where text=txt and id_person=person and year=yr)=0 then
		insert into comments (text,id_department,id_category,id_person, year) Select txt,(Select id_department from departments_category d where d.id_category=cat),cat,person, yr;
		Select 'Comment Inserted';
	else
		Select 'Duplicate Text';
	end if;
else
	update comments set text=txt,date_edited=now() where id_comment=e;
	Select 'Comment Updated';	
end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `P_Ins_Vote` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `P_Ins_Vote`(in icomment int,in person int,in voteup tinyint,votedown tinyint)
BEGIN
INSERT INTO `adds`.`votes`(`id_person`,`id_comment`,`vote_up`,`vote_down`) Select person,icomment,voteup,votedown;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `P_Sel_Course_View` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `P_Sel_Course_View`()
SELECT ct.name 'course',CONCAT('<a href="mailto:',p.email,'">',p.name,'</a>') 'person',c.date_start,c.date_finish,payment_confirmed,pc.acc_price_day,pc.acc_qty,pc.acc_price_day*pc.acc_qty 'Accomodation', course_contribution,pc.acc_price_day*pc.acc_qty+course_contribution 'Total', pc.deposit,pc.full_pay,pc.cart_open,a.name 'accomodation'
FROM people_courses pc
inner join people p
 on p.id_people = pc.id_people
inner join courses c
	on c.id_course=pc.id_course
inner join course_types ct
	on ct.id_type=c.course_type
left join accomodation a
	on a.id_accomodation=pc.id_accomodation
order by ct.name,c.date_start,payment_confirmed,p.name ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `P_Sel_Get_Filters` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `P_Sel_Get_Filters`(primarycat varchar(50),secondcat varchar(50))
BEGIN
                                                                                                       
 if primarycat='' then                                                                                    
											
 	Select count(category_name),category_name fROM adds.stuff_articles                      
 	group by category_name                                                                               
 	order by count(category_name) desc;                                                                  
 else                                                                                                  
 	if secondcat='' then                                                                                    
 		                                                      
 		Select count(category_2_name),category_2_name                                                      
 		fROM adds.stuff_articles a                                                            
 		where category_name=primarycat                                                               
 		group by category_2_name                                                                           														
 		order by count(category_2_name) desc;                                                              
 	else                                                                                                 
 		Select count(category_3_name),category_3_name fROM adds.stuff_articles                
 		where category_2_name=secondcat                                                                  
 		group by category_3_name                                                                           
 		order by count(category_3_name) desc;                                                               
	end if;
end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `P_Sel_Get_stats` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `P_Sel_Get_stats`()
BEGIN
SELECT `stats`.`comments`,
    `stats`.`pos_votes`,
    `stats`.`neg_votes`,
    net_votes,
    `stats`.`total_votes`,
    `stats`.`updated`,
    `stats`.`articles`
FROM `adds`.`stats`;

  
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `P_Sel_Vote_count` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `P_Sel_Vote_count`(in icomment int,in person int,in voteup tinyint,votedown tinyint)
BEGIN
Select count(*) 'count' from adds.votes v
where v.id_comment=icomment and v.id_person=person and v.vote_up=voteup
and v.vote_down=votedown;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `p_update_article_categories` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_update_article_categories`()
BEGIN
update stuff_articles set id_stuff=right(streamid,9);
update stuff_articles set category_name=replace(replace(streamID,'stuff/',''),id_stuff,'');
update stuff_articles set category_2_name=category_name;
update stuff_articles set category_name=left(category_2_name,locate('/',category_2_name)-1);
update stuff_articles set category_2_name=replace(category_2_name,CONCAT(category_name,'/'),'') ;
update stuff_articles set category_3_name=category_2_name ;
update stuff_articles set category_2_name=left(category_2_name,locate('/',category_2_name)-1) ;
update stuff_articles set category_3_name=replace(category_3_name,CONCAT(category_2_name,'/'),'') ;
update stuff_articles set category_3_name=replace(category_3_name,'/','');
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `P_Update_Article_Comment_Count` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `P_Update_Article_Comment_Count`()
BEGIN
Update Stuff_Articles s
inner join
(SELECT sa.id,sa.streamId,sa.streamTitle, count(*) 'comments' ,sum(sc.negVotes)+SUM(sc.posVotes) 'votes' ,sa.commentCount,sa.has_comments
  FROM adds.Stuff_Articles sa
  inner join adds.Stuff_Comments sc
	on sa.streamID=sc.streamID
  group by sc.streamId,sa.streamId,sa.commentCount,sa.has_comments,sa.streamTitle) a
  on a.id=s.id
  set s.commentCount=a.comments,vote_count=a.votes;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `P_Update_Comment_Characters` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `P_Update_Comment_Characters`()
BEGIN
Update Stuff_comments set commenttext=replace(commenttext,'€œ','');
Update Stuff_comments set commenttext=replace(commenttext,'€','');
update Stuff_Comments set commentText=REPLACE(commentText,'Â','');
update Stuff_Comments set commentText=REPLACE(commentText,'€™','''');
update Stuff_Comments set commentText=REPLACE(commentText,'â€™','''');
update Stuff_Comments set commentText=REPLACE(commentText,'â€','''');
update Stuff_Comments set commentText=REPLACE(commentText,'\n',''); 
update Stuff_Comments set commentText=REPLACE(commentText,'â™','');
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `P_Update_stats` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `P_Update_stats`()
BEGIN
if (select DATE_ADD(updated, INTERVAL 10 MINUTE)  from stats)<current_timestamp then
delete from stats;
INSERT INTO `adds`.`stats`
(`comments`,
`pos_votes`,
`neg_votes`,
net_votes,
`total_votes`,
`articles`,`updated`)
SELECT COUNT(*) ,SUM(posVotes)'pos',SUM(negVotes) 'neg',SUM(totalVotes) 'net',SUM(posVotes)+sum(negVotes) 'totalVotes',
	(Select COUNT(*) from stuff_articles) 'articles' ,current_timestamp                                                                              
  FROM adds.Stuff_Comments  ;    
end if;																								

  
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `updateComments` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `updateComments`(article int ,_comment text)
BEGIN
Update Stuff_Articles set antidote_comment=replace(_comment,'''','''''') where ID= article;
                        
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-06-05 15:11:32
