System Overview:
The online bus registration system is designed to facilitate and streamline the process of registering students for school bus services. It encompasses features that manage user data, bus route assignments, and an approval workflow, ensuring a seamless experience for both users (students/parents) and system administrators (school staff). The system is supported by a database schema that maintains information about users and approved registration lists, with foreign key relationships ensuring data integrity.

Core Functionalities:
User Registration and Login:
New users can register by providing necessary personal details such as name, student ID, and parent contact information.
Returning users can log in using their credentials to access their dashboard.
Profile Management:
Users can update their personal information after logging in.
Parents or students can view their profile details and make necessary changes, which are updated in the database.
Bus Registration:
Users can submit a request to register for school bus services.
The system allows users to select a bus route and seat preferences based on available options.
Approval Workflow:
The system includes a mechanism where bus registration requests are reviewed by the school administration.
Upon approval, the student is added to an approved_list table, confirming their seat assignment.
Notifications and Updates:
Users receive updates regarding the status of their bus registration.
An option for notifications may be provided via email or through in-system alerts.
