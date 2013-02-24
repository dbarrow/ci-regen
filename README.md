Re:Gen
======
Codeigniter Based Web Resource Generator
###Overview:

RE:GEN enables developers to create web resources(RESTful web services) quickly and easily. 

While this system automates a lot, Codeigniter knowledge is a benefit.  Basic CRUD routes are quickly generated, but any custom resource can be added to the service’s controller.

The project was started when I got into client side applications.  When I began using backbone.js and saw the power of web resources, the idea came to mind.  Currently, I am using angular.js.  HERE is an article that has sparked my interest in authentication.

I have decided to keep everything as simple as possible.  I am using several open source projects in this project.  I have implemented some of these as modules, but have not added another layer of abstraction.  There is no template engine.  Straight Codeigniter $this->load->view().  While abstractions are neat, they add complexity to the application.
###Credits

- Codeigniter - www.link.com
- Jamie Rubelow - MY_Model www.link.com. In my opinion, the best off the shelf my_model
- Phil Sturgeon - CI Rest Server www.link.com.  Great implementation.  One of those libriaries    where I am not afraid of things not working.
- Mtsands - Tank Auth with Roles added www.link.com.  Tank auth seems to work out of the box pretty well.  I like the fact mtsands added role support.  Although roles are not used in this project, they may come in handy when implementing applications and authentification.  
- wiredesigns - HMVC www.link.com.  The standard codeigniter HMVC package
- Twitter Bootstrap - www.link.com.  Ok, what did you expect.


###Installation


Installation of RE:GEN is much like a base codeigniter install.

Download source.
Save to your public html directory. 

Example:  wamp/www, xampp/htdocs, etc...

Open the htaccess file in the root directory.  Edit the RewriteBase / at around line 120. If the source files have been placed in the root of the public html directory, leave the line as is.  If the files have been placed in a subdirectory of the public html directory, add your subdirectory.

Example:  RewriteBase /ci-regen/

Thanks to Lonnie Ezell’s project, CIBonfire, his htaccess file somehow allows us to not change the base_url in the application/config/config.php.  Do not alter the base_url unless you are having issues.  If anyone knows why the htaccess file works like this, let me know.
Create an empty MySQL Database

After installation, simply start your server and navigate to the application.  From here an install screen will appear.  

Enter you database credentials and initial user information.  

Click Install.  Hopefully there were no errors.  




###What does it do:

After installation, a simple GUI allows one to create new web services.  
Existing Services

After installation, two REST services are available, users and user_profiles.

To consume the services, navigate to the resource.  
Example:  localhost/ci-regen/api/users

Here you will see json output for all users.  Add an ‘id’ to the url and get that particular user.

Example:  localhost/ci-regen/api/users/1

###Using Existing Services

There are several HTTP tools available.  I like using Postman for Chrome.  Install Postman.

Run Postman.  Enter RESTful route. Available routes are as follows.

Get
localhost/ci-regen/api/users
localhost/ci-regen/api/users/1
localhost/ci-regen/api/users/?id=1
localhost/ci-regen/api/users/with_profiles
localhost/ci-regen/api/users/with_profiles/1
localhost/ci-regen/api/users/with_profiles/?id=1

Post
localhost/ci-regem/api/users/

Click x-www-url-encoded
add values. see users db table for available fields

Put



###Creating New Services 

From the services index page, click the + button.

Set service name.  Application conventions use this name for the controller name, the model name, and the database name.  The service name is also added to the services table.  

Example:
service name: books
Controller: books.php
Model: books_model.php
database table name: books
service database record name field: books

Create primary key.  Application conventions use ‘id’ as the primary key.  Currently the primary key is hard coded to ‘id’.  Allowing any id is a simple enhancement that will be added as time permits.

Add as many fields as you would like.	

Currently, this page has no validation and additional fields are added via jquery.  If the page is refreshed, navigated back to or there is a service builder error, the fields will have to be entered again.  This is something that needs to be fixed.

Installation Recap:

1.  Download
2.  Save files to your localhost like a normal Codeigniter installation.
3.  Open htaccess file at root of project folder.  Set the URL to the folder your project is in.
4.  Open phpMyAdmin or whatever and create a database.
5.  Go to localhost/projectfolder
6.  install screen appears
After getting the application set up on a server, the first route is install.  


###Routes:

Two custom routes have been created in config/routes.php.

<pre>
$route['api/' . '(:any)/(:num)']	= "$1/index/$2";
$route['api/' . '(:any)']	= "$1";
</pre>

By default, the functions available for the generated controllers are

index_get()
index_post()
index_put()
index_delete()

Phil’s Rest Controller requires every method to have the HTTP verb appended to the function name.  However, when calling the function, the HTTP verb is not included in the call.

Example:

 api/books/index will route to the appropiate method relying on the HTTP headers.

The first custom route enables us to not call the index method in the url.
Example:
<pre>
get
Without Custom route:  www.domain.com/api/books
With custom route:       www.domain.com/api/books

post
Without Custom route:  www.domain.com/api/books/index
With custom route:       www.domain.com/api/books

put
Without Custom route:  www.domain.com/api/index/books/index/[:id]
With custom route:        www.domain.com/api/books/[:id]

delete
Without Custom route:  www.domain.com/api/books/index/[:id]
With custom route:  www.domain.com/api/books/[:id]
</pre>
###Version Control:

In the first and second route, ‘api/’ is prepended to the routes.  Simply change these routes in subsequent versions.  

Example:

<pre>
$route['api2/' . '(:any)/(:num)']	= "$1/index/$2";
$route['api2/' . '(:any)']	= "$1";
</pre>

I know, simple but effective.

