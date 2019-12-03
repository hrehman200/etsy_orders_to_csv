<?php
/*
 * login_with_etsy.php
 *
 * @(#) $Id: login_with_etsy.php,v 1.1 2014/03/17 09:45:08 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require(dirname(__DIR__).'/httpclient/http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->debug = false;
	$client->debug_http = true;
	$client->server = 'Etsy';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_etsy.php';

	$client->client_id = 'ycq1n4woweqzeag3g81x8pgq'; $application_line = __LINE__;
	$client->client_secret = '52i4xvy86s';
	$client->scope = 'email_r transactions_r treasury_r';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Etsy Developers page https://www.etsy.com/developers/register , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to key string and client_secret with shared secret. '.
			'The Callback URL must be '.$client->redirect_uri);

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://openapi.etsy.com/v2/users/__SELF__', 
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success) {

	    // 256462257
        $success = $client->CallAPI(
            'https://openapi.etsy.com/v2/users/256462257/transactions',
            'GET', array(), array('FailOnAccessError'=>true), $transactions);

        if($success) {
            var_dump($transactions);
        }

    }

?>