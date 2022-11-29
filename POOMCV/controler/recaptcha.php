<?php
 /**
  * OBSOLETE
   * Google Recaptcha controler 
   * adapatation de l'exemple google en class php
   * Dans cette class il y a plusieurs façon d'accéder aux données avec la même méthode (function).
   * c'est juste pour s'amuser un peu...
   * @package    non testé
   * @subpackage Controller
   * @author     google 
   */
class Recaptcha {
// variable privées 
	private  $recaptcha;
	private  $secret_key ;

    public function __construct(?string $recaptcha, ?string $secret_key )
    {
		$this->recaptcha = $recaptcha;
		$this->secret_key = $secret_key;
        return self::isValidRecaptcha($recaptcha, $secret_key);
    }

	/**
	 * isValidRecaptcha verify if the recaptcha reponse is ok or not*
	 * @param $recaptcha = $_POST['g-recaptcha-response']; 
	 * @param $secret_key = google recaptcha secretkey look at https://www.google.com/recaptcha/admin
	 * @return bool
	 */
	public static function isValidRecaptcha(string $recaptcha, string $secret_key):boolean
	{	
		// Storing google recaptcha response
		// in $recaptcha variable
		// $recaptcha = $_POST['g-recaptcha-response'];

		// Put secret key here, which we get
		// from google console
		// https://www.google.com/recaptcha/admin
		// $secret_key = '6LcBzCYjAAAAAP9leZd2O4VNSw6wfQ1CTYPYdV1B'; 

		// Hitting request to the URL, Google will
		// respond with success or error scenario
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret='
			. $secret_key . '&response=' . $recaptcha;

		// Making request to verify captcha
		$response = file_get_contents($url);

		// Response return by google is in
		// JSON format, so we have to parse
		// that json
		$response = json_decode($response);

		// Checking, if response is true or not
		if ($response->success == true) {
			echo '<script>alert("Google reCAPTACHA verified")</script>';
			return true;
		} else {
		
			echo '<script>alert("Error in Google reCAPTACHA")</script>';
			return false;
		}
	}

	/**
	 * isValidRecaptcha verify if the recaptcha reponse is ok or not
	 * @param $recaptcha = $_POST['g-recaptcha-response']; 
	 * @param $secret_key = google recaptcha secretkey look at https://www.google.com/recaptcha/admin
	 * @return boolean
	 */
	public static function isValidRecaptcha2():boolean
	{	
		// Storing google recaptcha response
		// in $recaptcha variable
		// $recaptcha = $_POST['g-recaptcha-response'];

		// Put secret key here, which we get
		// from google console
		// https://www.google.com/recaptcha/admin
		// $secret_key = '6LcBzCYjAAAAAP9leZd2O4VNSw6wfQ1CTYPYdV1B'; 

		// Hitting request to the URL, Google will
		// respond with success or error scenario
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret='
			. $this->$secret_key . '&response=' . $this->$recaptcha;

		// Making request to verify captcha
		$response = file_get_contents($url);

		// Response return by google is in
		// JSON format, so we have to parse
		// that json
		$response = json_decode($response);

		// Checking, if response is true or not
		if ($response->success == true) {
			echo '<script>alert("Google reCAPTACHA verified")</script>'; // affiche un messagebox d'alert en javascript
			return true;
		} else {
		
			echo '<script>alert("Error in Google reCAPTACHA")</script>'; // affiche un messagebox d'alert en javascript
			return false;
		}

	}

}	


?>
