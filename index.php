<?php
/**
* @author Anthony Gaudio
* @category ANM293 - Advanced PHP
* Project 03
* Set Up Swiftmailer Library
*/

/*
 * Set timezone, tell php to NOT display errors, turn on logging function,
 * and extend the default execution time.  
 * the error reporting parameter E_ALL will display All errors and warnings,
 * as supported, except of level E_STRICT. 
 * the error reporting parameter of E_STRICT will Enable to have PHP suggest
 *  changes to your code. 
 */
date_default_timezone_set('America/Detroit');
  @ini_set('display_errors','Off');
  @ini_set('log_errors','On');
  @ini_set('max_execution_time', 300);
  error_reporting(E_ALL | E_STRICT);

/*
 * Test which type of path separator is being used on the server
 * then define which directon the slash should go, and assign it to
 * global variable called SLASH
 */
 if( PATH_SEPARATOR  == ';' )
    define('SLASH','\\');  
  else
    define('SLASH','/'); 

 /*
  * Define global varialbe called APP_PATH based on the results of the realpath
  * function.  This will return the path of the current file, in this case the
  * index.php file you are viewing.
  */
  define('APP_PATH', realpath(dirname(__FILE__)));
  /*
   * Concatenate a new global variable called SWIFT_PATH with the APP_PATH, the
   * appropriate slash, quoted text (folder name), another slash, and subfolder
   * name.
   */
  define('SWIFT_PATH',APP_PATH . SLASH . 'library' . SLASH . 'SwiftMailer' . 
  SLASH . 'V4.0.6' . SLASH . 'lib');
 /*
  * Globally set included file path and name by concatenating a path and file
  * structure from previously set global variables.
  */
  set_include_path(realpath(SWIFT_PATH . SLASH)); 
  /*
   * Include the file below from the defined include path from above.
   */
@(include_once('swift_required.php'));
  
  
  /*
   * Give this section of code a 'try', if all goes well, no worries, if there
   * is a problem (exception) the 'catch' block below will catch it.
   */
  try {
  /*
   * Create the transport object using the smtp transport method, parameters:
   * mail host, port number, and 'ssl' sets Secure Socket Layers encryption.
   * Below that the email account username and password is set.
   * Store all of this information in the $transport variable
   */
$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('tonyforschool')
  ->setPassword('advancedphp')  ;

  /*
   * Create the Mailer using your created Transport
   * Create new mailer object - Store it in $mailer variable
   */
$mailer = Swift_Mailer::newInstance($transport);
/*
 * Create an new instance of a message - store it in the $message variable
 */
$message = Swift_Message::newInstance();
/*
 * Get existing header information frm the message object
 */
$headers = $message->getHeaders();
/*
 * add text header information to the headers object - this is viewable if you
 * view the source of the email received.
 */
$headers->addTextHeader('ANM293', 'CNM-270');
/*
 * Set the subject of the message.
 */
$message->setSubject('Tony Gaudio, SWIFT Mailer 4.0.6');

/*
 * Set a From: address including a name - need to use array if more than one
 * from address, OR if you include a name.
 */
$message->setFrom(array('tonyforschool@gmail.com' => 'Tony Gaudio'));
$message->setReplyTo(array('tonyforschool@gmail.com' => 'Tony Gaudio'));
/*
 * Same thing goes for the To field, need to use array if more than one to
 * address, OR if you include a name.
 */
$message->setTo(array(
  'wireman131@chartermi.net' => 'Anthony Gaudio'));
/*
 * Bounce path for messages that can not be delivered, or a Reply To address.
 */
$message->setReturnPath('tonyforschool@gmail.com');
/*
 * Set the body of the message, followed by the format, in this case 'text/html'
 */
$message->setBody('I rock at PHP', 'text/html');
/*
 * Create result variable - assign it the result of the send method of the 
 * mailer object.  result will be a number - 0 means the message failed, any
 * other digit tells you how many messages were sucesfully delivered.
 */
$result = $mailer->send($message, $failures);  

if (!$result)
{
  echo "Failures:";
  print_r($failures);
  /*
   * Quoted from the support forums -->
   * The only type of failures that will go into that array are immediate 
   * failures such as "Relay denied", "Malformed address", 
   * "Service unavailable" and such like errors. <--- */
  trigger_error('Send Error Message From IF Statement : ' . $failures,E_USER_NOTICE);
} else {
  echo "Another amazing success story.<br/>";
 /*
 * Output to browser the total (%d = decimal) sent messages.
 */
  printf("Sent %d messages\n", $result);
}

/*
 * Catch block for the above try.  If there is an exception anywhere above it
 * will be assigned to $e
 */
  } catch(Exception $e)
  {
  /*
   * I'm not sure if this is the proper way to do this, BUT it works.
   * If there is an exception caught above, send it to the log using this.
   */
  trigger_error('Send Error Message: ' . $e,E_USER_NOTICE);
  }


?>