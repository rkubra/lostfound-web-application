
# Lost and Found System

## Overview
The Lost and Found System is a web-based application designed to help users report lost items and connect with those who have found them. Built with a user-friendly interface, this system allows individuals to post, search, and manage lost and found items efficiently. The project utilizes modern web technologies to provide a seamless experience for both administrators and public users.

## Features
- Public Interface:Users can view published lost and found items, filter by category, and submit found items for approval.
- Admin Dashboard: Administrators can manage categories, items, users, and system settings with full CRUD (Create, Read, Update, Delete) functionality.
- Responsive Design:The system is optimized for desktop and mobile devices with a clean, intuitive layout.
- Dynamic Content:Supports dynamic updates to public site content via the admin panel.
- Security: Basic authentication for admin access to protect sensitive operations.

## Technologies Used
- Frontend: HTML, CSS (custom styling), JavaScript
- Backend:PHP
- Database: MySQL
- Framework/Library:Bootstrap v5, 
- Server:XAMPP (Apache and MySQL)
- Editor: VS Code

## Installation and Setup

### Requirements
- XAMPP (or any local web server with PHP and MySQL support)
- Web browser (e.g., Chrome, Firefox)

### Steps
1. Install XAMPP:
   - Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/).
   - Start Apache and MySQL services via the XAMPP Control Panel.

2. Download the Source Code:
   - Clone or download the project repository to your local machine.

3. Configure the Project:
   - Extract the downloaded zip file.
   - Copy the extracted folder (e.g., `lostfound`) and paste it into the `htdocs` directory of XAMPP (e.g., `C:\xampp\htdocs\lostfound`).

4. Set Up the Database:
   - Open your web browser and navigate to `http://localhost/phpmyadmin`.
   - Create a new database named `lfis_db`.
   - Import the provided SQL file (`lfis_db.sql`) located in the `database` folder of the project.

5. Run the Application:
   - Open your web browser and go to `http://localhost/lostfound`.
   - Log in with the default admin credentials:
     - Username: `kabralkabral`
     - Password: `adminpass`


## Usage
- Public Users: Visit the homepage to browse lost and found items, filter by category, or submit a found item (subject to admin approval).
- Administrators: Log in to the admin panel to manage users, categories, items, and system information. Use the dashboard to approve or update listings.

## Contributing
Feel free to fork this repository, make improvements, and submit pull requests. Contributions to enhance features, fix bugs, or improve documentation are welcome!

## License
This project is licensed under the MIT License. See the `LICENSE` file for details.

## Contact
For questions or support, please reach out via [rkubra.it@gmail.com].

