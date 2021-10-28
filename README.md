# LMS

A learning management system to deliver courses and quizzes to users provided by instructors filling in simplified EML templates.

EML templates resembling a more simplified and readable XML structure are provided to fill in course contents and store in MySQL database.
The EML allows for non-uniform content to be added with headers, lists, tables, images and more. 

Contents requested by user selection are retreived and fed to the server side php parser to translate the contents to a renderable HTML format. 
Quizzes are dynamically created allowing evaluation based on a JSON array answer scheme created from the EML. 
