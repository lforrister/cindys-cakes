<?php 

// Hi Lindsay!

// Here's how I'd go about it. Feel free to let me know if you have any questions about anything!

// In mt_api_2015 (make sure your presentation is pointing to your local if you want it to be running your code!)
// also i looked in our API code and it looks like we don't have code for passing POST parameters in the request to the API. we can add that, but for now we can get it working by just passing the parameters to the API as GET. Note that we can still use POST on the user form submission side of it! the api call is separate

// in docroot/src/Controllers/RootController.php I'd add:
use \Compendium\Data\MySqlClient as MySql;
// at the top (there are similar lines, just put it by them)

// in the body of the class I'd add:
public function doContactFormSubmission() {
// ...
}

// in docroot/src/Routes/MTRoutes.php next to where you see similar lines (e.g. line 96) I'd add:
$this->router->any($routePrefix . "/forms/contact", array('Controllers\RootController', "doContactFormSubmission"));

// This will mean that when your local api is hit at /v1/forms/contact then the method doContactFormSubmission will get called.

// In doContactFormSubmission I'd do something like:
$params = array(
	':firstName' => filter_var($_REQUEST["firstName"], FILTER_SANITIZE_STRING),
	':lastName' => filter_var($_REQUEST["lastName"], FILTER_SANITIZE_STRING),
	':whatever' => filter_var($_REQUEST["whatever"], FILTER_SANITIZE_STRING)
);
MySql::executeNonQuery('INSERT INTO my_form_table (firstName, lastName, whatever) VALUES (:firstName, :lastName, :whatever)', $params);

// this will automatically parameterize the statement for you, replacing the keys in $params wherever it finds them in the SQL statement. note that it's not super clever, so if you have something like:
$params = array(
	':myVar' => 'val1',
	':myVar2' => 'val2'
);
MySql::executeNonQuery('INSERT INTO my_other_table (col1, col2) VALUES (:myVar, :myVar2)', $params);
// then the :myVar2 will probably get messed up because it'll first find the ":myVar" part of ":myVar2" and replace it. the internal SQL would look something like:
// INSERT INTO my_other_table (col1, col2) VALUES (`val1`, `val1`2)
//                                                         ^^^^^^^
// and choke the SQL parser. as long as you make sure that the different keys aren't substrings of each other then you don't have to worry!




// In mt_pres_2015 I'd add to docroot/lib/Febe/Controllers/CelebrateWithUsController.php :
// at the top after "use Bronto_Api":
use Febe\Framework\Data_PlatformAPI as API;

// then in the controller class's body I'd add something like:
public function doContactFormSubmission() {
	$params = array(
		'firstName' => filter_var($_REQUEST["firstName"], FILTER_SANITIZE_STRING),
		'lastName' => filter_var($_REQUEST["lastName"], FILTER_SANITIZE_STRING),
		'whatever' => filter_var($_REQUEST["whatever"], FILTER_SANITIZE_STRING)
	);
	// passing as GET params here because the API doesn't handle POST yet
	// also i'm not checking if there were any API errors here, we can add that in once we have it working end-to-end
	API::callAPI('/forms/contact?'.http_build_query($params));
	// send them to a thank you page.
	header('Location: /thank-you.html');
}

