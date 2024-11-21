DROP DATABASE IF EXISTS wiki_logs;
CREATE DATABASE IF NOT EXISTS wiki_logs;

USE wiki_logs;

DROP TABLE IF EXISTS
  domain_name,
  page_title,
  page_views;

CREATE TABLE page_views (
    domain_name VARCHAR(100) NOT NULL,
    page_title VARCHAR(1000) NOT NULL,
    page_views int NOT NULL
);

LOAD DATA
  INFILE '/var/lib/mysql-files/pageviews.txt'
INTO TABLE
  page_views
  FIELDS TERMINATED BY ' '
  LINES TERMINATED BY '\n'
  (@col1, @col2, @col3, @col4)
SET
  domain_name = @col1,
  page_title = @col2,
  page_views = @col3;
