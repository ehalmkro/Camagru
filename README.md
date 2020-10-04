# Camagru

The first web branch project at Hive Helsinki, a simple Instagram-like web app made using _good old_ PHP, vanilla JS, MySQL and non-JS Bootstrap.
My main focus on this project was learning the model-view-controller design pattern, which I feel I implemented into the project well. 
Design and UX/UI wise I kept the project deliberately simple, resorting to Bootstrap's default layouts and themes.

<p align="center">
 <img width="500" src="https://github.com/ehalmkro/Camagru/blob/master/mainview.png" />
</p>

The app is a basic image gallery with comments and likes. Logged-in users can take pictures with their webcam or upload png/jpg files from their device.
These pictures can be overlayed with one or multiple stickers.

<p align="center">
 <img width="500" src="https://github.com/ehalmkro/Camagru/blob/master/webcamview.png" />
</p>

Uploaded images are displayed in an Instagram-like gallery view, all of the pictures are commentable and likeable to a logged-in user. Likes and comments are posted using 
Javascript's fetch API. If enabled, users get email notifications on new comments in their pictures.

<p align="center">
 <img width="500" src="https://github.com/ehalmkro/Camagru/blob/master/gallery.gif" />
</p>

User creation requires a complex enough password and an email confirmation. Users can later reset their password via email. 

Database interactions are handled with [PDO](https://www.php.net/manual/en/book.pdo.php) using a _singleton_ class limiting the concurrent database connections to only one instance.
The MySQL database has three tables, users, images and likes/comments.
