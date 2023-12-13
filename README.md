# HireLatamTest
+ Dockerized Wordpress instance 
+ Mariadb

# How to run Proyect

1. Clone or uncompress file: 
```
$ git clone git@github.com:genaro14/HireLatamTest.git
```
2. Access folder:
```
$ cd hireLatamTest
```
3. Run
```
$ docker-compose up -d
```
## Root Access
+ user: wordpress
+ password: wordpress

## Wordpress Access
 Configure on install
+ user: user
+ password: password

## How to see changes
### Theme 
Ensure that theme is properly enabled after setup:      
 On Appearance > Themes > Activate Theme: "Hire Latam Test Children Theme" 
## Pages
On wordpress wp-admin create a page using the template 'Session Scheduler' and access it.       
1. Slug 'session' on "http://localhost/session/"      
Link: [Session scheduler](http://localhost/session/)  

2. Page for confirmation  
Slug 'confirmation' with Template 'Confirmation Page'
Slug 'session' on "http://localhost/confirmation/"      
Link: [Confirmation](http://localhost/confirmation/) 

Activate the Session Scheduler Plugin on 
Plugins> Session Scheduler 'Activate'

On wordpress admin sidebar > Scheduled Sessions 
## Notes
I tried to maintain an agnostic approach for the validations and the calendar, probabbly using 3rd party libraries would be faster, the intention was to keep things simple.

### Tests
I have included a basic test file that checks for the front page to be present. Further test are needed for the front and back. This was due to time limit. 
Example run.
```
$ vendor/bin/phpunit tests/SessionSchedulerTest.php
```



