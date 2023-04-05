Laravel Project README
Prerequisites
Before running the project, ensure that you have the following software installed on your system:

PHP 7.3 or higher
Composer
dompdf/dompdf
Node.js
NPM
MySQL
Installation
To install the project, follow these steps:

Clone the repository to your local machine using git clone https://github.com/your-username/your-project.git.
Navigate to the project directory using the command cd your-project.
Install the dependencies using "composer install" and "composer require dompdf/dompdf".
Create a new file named .env in the root directory of the project by copying the contents of the .env.example file using the command cp .env.example .env.
Update the .env file with your database details.
Run the migrations using the command php artisan migrate.
Generate Google mail password and place in env file
Instructional Video: https://www.youtube.com/watch?v=qk8nJmIRbxk&list=PLh_kO0_UG_pdcQ3uDcfPcM6rexiJO9uDM&index=4
Start the local development server using the command php artisan serve.
Usage
To use the project, navigate to http://localhost:8000 in your web browser.

Testing
To run the tests for the project, use the command php artisan serve.

Contributors
Shweth Maharaj
Jazbia Naem
Neeraj Ghutla
Tushaar Sharma
