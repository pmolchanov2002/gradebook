# gradebook
Grade Book is a PHP / Symfony project to collect/store grades of students

## Purpose

Main purpose of the application is to allow teachers enter quaterly grades including attendance, discipline and delligence.
Adminitrators can notify teachers via e-mail about the next grading period.

## Foundation

Application is based on the Symfony 2.x framework.
It's powered by MySQL database and Doctrine ORM (which is part of the Symfony 2.x framework)
Front end is powered by Bootstrap and is nicely resized on both mobile (Android, iPhone, iPad) and desktop (Windows, Mac, Linux) screens.

## Maintenence

Minimum maintenance is required. Code can be easily adjusted for specific school needs with minimum knowledge of PHP/ORM/MySQL.
Some reports are in Russian!

#Howto run

1. Create MySQL database and upload schema from /app/schema/gradebook.sql folder
2. Update DB and Mail server connection settings in the /app/config/parameters.yml file
3. Start local Symfony server:

```
cd gradebook
php app/console server:run
```

4. Open http://127.0.0.1:8000/ in the browser. 
5. Default administrator username: admin, password: 123
6. Enjoy

## Functionality

Application allow to:

1. Setup school years (years of study)
2. Setup cources
3. Setup users (administrators, students and teachers)
4. Setup classes
5. Setup periods
6. Setup lessons (relation between teacher, period, class and course)

Reports:

1. Grades for each student and all students
2. Teachers who didn't provide grades for students

E-mail support:

1. Letter to all teachers to provide grades to students
2. Follow up message to teachers with pending reports

##Future plans

1. Add support for parents
2. Distribute grades to parents via e-mail
3. School web-site CMS support

#Coding
Any help with code review and new functionality is highly appreciated.
Application doesn't have unit-tests yet that are highly appreciated.

