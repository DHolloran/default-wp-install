<?php
/**
* 
* Wrapper functions for the ShareYourCart API
* Version: 1.2
* Copyright (c) 2011 Barandi Solutions
* 
**/
global  $SHAREYOURCART_API, 
        $SHAREYOURCART_API_REGISTER, 
        $SHAREYOURCART_API_RECOVER, 
        $SHAREYOURCART_API_ACTIVATE, 
        $SHAREYOURCART_API_DEACTIVATE,
        $SHAREYOURCART_API_CREATE,
        $SHAREYOURCART_API_VALIDATE;

$SHAREYOURCART_API = 'https://www.shareyourcart.com';
$SHAREYOURCART_API_REGISTER = $SHAREYOURCART_API.'/account/create';
$SHAREYOURCART_API_RECOVER = $SHAREYOURCART_API.'/account/recover';
$SHAREYOURCART_API_ACTIVATE = $SHAREYOURCART_API.'/account/activate';
$SHAREYOURCART_API_DEACTIVATE = $SHAREYOURCART_API.'/account/deactivate';
$SHAREYOURCART_API_CREATE = $SHAREYOURCART_API.'/session/create';
$SHAREYOURCART_API_VALIDATE = $SHAREYOURCART_API.'/session/validate';


/**
*
*  Start a new session
*  @returns an array with the token and the $_SESSION id
*/
function shareyourcart_startSessionAPI($params)
{
        global $SHAREYOURCART_API_CREATE;
        
        $session = curl_init($SHAREYOURCART_API_CREATE);

        // Tell curl to use HTTP POST
        curl_setopt($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($params));
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($session);
        $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);

        //if the operation was not succesfull, print the error
        if($httpCode != 200)
        {
                print_r($response);
                exit;
        }
        
        //decode the result
        $results = json_decode($response,true);
        
        //find the token
        if(isset($results['token']))
        {
                //link the token with the current cart ( held in session id )
                $data=array(
                'token' => $results['token'],
                'session_id' => session_id(),
                );
                
                //a token was obtained, so redirect the browser
                header("Location: $results[session_url]", true, 302);
                return $data;
        }
        else
        {
                //show the raw response received ( for debug purposes )
                header("HTTP/1.0 403");
                print_r($response);
                exit;
        }
}

/**
*
*  Validate the received coupon with ShareYourCart.com
*  Stops the php execution if the coupon is not valid. Since this is an API, it's ok to stop
* 
*/
function shareyourcart_ensureCouponIsValidAPI()
{
        global $SHAREYOURCART_API_VALIDATE;
        
        /*********** Check input parameters ********************************/
        if(!isset($_POST['token'], $_POST['coupon_code'], $_POST['coupon_value'], $_POST['coupon_type']))
        {
                header("HTTP/1.0 403");
                exit;
        }

        //urlencode and concatenate the POST arguments
        $params = array(
                'token' => $_POST['token'],
                'coupon_code' => $_POST['coupon_code'],
                'coupon_value' => $_POST['coupon_value'],
                'coupon_type'=>$_POST['coupon_type'],
                );

        //make the API call
        $session = curl_init($SHAREYOURCART_API_VALIDATE);

        // Tell curl to use HTTP POST
        curl_setopt($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($params));
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($session);
        $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);
        
        //if the operation was not succesfull, print the error
        if($httpCode != 200)
        {
                header("HTTP/1.0 403");
                exit;
        }
        
        $results = json_decode($response,true);    

        //if the result is not valid, print it
        if(!isset($results['valid']) || !$results['valid'])
        {
                header("HTTP/1.0 403");
                print_r($response);
                exit;
        }
}

/**
*
*  Create a new account
*  @returns an array with the client_id and app_key or FALSE
*  It will not throw an exception
*/
function shareyourcart_registerAPI($secretKey, $domain, $email)
{
        global $SHAREYOURCART_API_REGISTER;
        
        //urlencode and concatenate the POST arguments
        $params = array(
                'secret_key' => $secretKey,
                'domain' => $domain,
                'email' => $email,
                );

        //make the API call
        $session = curl_init($SHAREYOURCART_API_REGISTER);

        // Tell curl to use HTTP POST
        curl_setopt($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($params));
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($session);
        $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);
        
        //if the operation was not succesfull, return FALSE
        if($httpCode != 200)
        {
                //show the response
                //print_r($response);
                return FALSE;
        }
        
        //return the response after decoding it
        return json_decode($response,true);    
}

/**
*
*  Set the account status
*  @returns TRUE or FALSE
*/
function shareyourcart_setAccountStatusAPI($secretKey, $clientId, $appKey, $activate = true)
{
        global $SHAREYOURCART_API_ACTIVATE, $SHAREYOURCART_API_DEACTIVATE;
        
        //urlencode and concatenate the POST arguments
        $params = array(
                'secret_key' => $secretKey,
                'client_id' => $clientId,
                'app_key' => $appKey,
                );

        //make the API call
        $session = curl_init($activate ? $SHAREYOURCART_API_ACTIVATE : $SHAREYOURCART_API_DEACTIVATE);

        // Tell curl to use HTTP POST
        curl_setopt($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($params));
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($session);
        $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);
        
        //notify the caller
        //if the operation was not succesfull, return FALSE
        if($httpCode != 200)
        {
                //show the response
                //print_r($response);
                return FALSE;
        }
        
        return TRUE;
}

/**
*
*  Recover the credentials for an account
*  @returns TRUE or FALSE
*/
function shareyourcart_recoverAPI($secretKey, $domain, $email)
{
        global $SHAREYOURCART_API_RECOVER;
        
        //urlencode and concatenate the POST arguments
        $params = array(
                'secret_key' => $secretKey,
                'domain' => $domain,
                'email' => $email,
                );

        //make the API call
        $session = curl_init($SHAREYOURCART_API_RECOVER);

        // Tell curl to use HTTP POST
        curl_setopt($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($params));
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($session);
        $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);
        
        //if the operation was not succesfull, return FALSE
        if($httpCode != 200)
        {
                //show the response
                //print_r($response);
                return FALSE;
        }
        
        return TRUE;    
}

?>