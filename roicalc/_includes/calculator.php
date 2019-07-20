<?php

header('Content-Type: application/json');

// function http_request($url, $fields, $http_verb)
// {
// 	$data = http_build_query($fields);

// 	if ('GET' == $http_verb)
// 	{
// 		$url = $url . '?' . $data;
// 	}

// 	$ch = curl_init();
// 	curl_setopt($ch, CURLOPT_URL, $url);

// 	if ('POST' == $http_verb)
// 	{
// 		curl_setopt($ch, CURLOPT_POST, 1);
// 		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
// 	}

// 	curl_setopt($ch, CURLOPT_FAILONERROR, 0);
// 	curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
// 	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
// 	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
// 	$retval       = curl_exec($ch);
// 	$curl_error   = curl_error($ch);
// 	$curl_errno   = curl_errno($ch);
// 	$curl_getinfo = curl_getinfo($ch);
// 	curl_close($ch);

// 	return array("curl_result" => $retval, "curl_error" => $curl_error, "curl_errno" => $curl_errno, "curl_getinfo" => $curl_getinfo);
// }

$method        = (isset($_POST['method'])) ? $_POST['method'] : false;
$field_data    = (isset($_POST['field_data'])) ? $_POST['field_data'] : false;
$ap_voice      = (isset($_POST['ap_voice'])) ? $_POST['ap_voice'] * 1 : false;
$ap_chat       = (isset($_POST['ap_chat'])) ? $_POST['ap_chat'] * 1 : false;
$ap_email      = (isset($_POST['ap_email'])) ? $_POST['ap_email'] * 1 : false;
$ap_social     = (isset($_POST['ap_social'])) ? $_POST['ap_social'] * 1 : false;
$aa_agents     = (isset($_POST['aa_agents'])) ? $_POST['aa_agents'] * 1 : false;
$aa_rate       = (isset($_POST['aa_rate'])) ? $_POST['aa_rate'] / 100 : false;
$cl_customers  = (isset($_POST['cl_customers'])) ? $_POST['cl_customers'] * 1 : false;
$cl_value      = (isset($_POST['cl_value'])) ? $_POST['cl_value'] * 1 : false;
$sl_aht        = (isset($_POST['sl_aht'])) ? $_POST['sl_aht'] * 1 : false;
$sl_fcr        = (isset($_POST['sl_fcr'])) ? $_POST['sl_fcr'] / 100 : false;
$sl_aai        = (isset($_POST['sl_aai'])) ? $_POST['sl_aai'] / 100 : false;
$sl_clv        = (isset($_POST['sl_clv'])) ? $_POST['sl_clv'] / 100 : false;
$burden        = 30;
$cp_call       = 5;
$cp_chat       = 2;
$cp_email      = 3;
$cp_social     = 4;
$onboard       = 10000;
$ap_val        = (($ap_voice + $ap_chat + $ap_email + $ap_social) * $sl_aht * ($burden / 3600) * 12) + ($ap_voice * $sl_fcr * $cp_call * 12) + ($ap_chat * $sl_fcr * $cp_chat * 12) + ($ap_email * $sl_fcr * $cp_email * 12) + ($ap_social * $sl_fcr * $cp_social * 12);
$aa_val        = $aa_agents * $aa_rate * $sl_aai * $onboard;
$cl_val        = $cl_customers * $cl_value * $sl_clv * 2;
$total_savings = $ap_val + $aa_val + $cl_val;

$savings_values = array(
	"AgentAttritionSavings"          => $aa_val,
	"AnnualAgentProductivitySavings" => $ap_val,
	"AnnualSavings"                  => $total_savings,
	"CustomerLifetimeValueSavings"   => $cl_val,
	);


if($method == 'roi')
{
	echo '{"ap_val":' . $ap_val . ', "aa_val":' . $aa_val . ', "cl_val":' . $cl_val . '}';
}

if($method == 'email')
{

	$form_values = array();
	$to          = $_POST['email_to'];
	//var_dump($field_data);

	//print "1-------1";

	parse_str($field_data, $form_values);

	//print "2-------2";
	//var_dump($form_values);
	//print "3-------3";
	$form_values2 = array();
	foreach ($form_values as $key=>$value){
		if (substr($key, 0, 3) != "ao_" ){
			$key=str_replace("_", " ", $key);
			$form_values2[$key]=$value;
		}
	}
//print "4-------4";
//var_dump($form_values2);
	$post_data   = array_merge($form_values2, $savings_values);
	//$post_data1 = str_replace("+","%20",$post_data);
	// Submit the data to ActOn (ROI Results)
	//print "5--------5";
	//var_dump ($post_data);
	//$acton_response = http_request('http://info.liveops.com/acton/eform/14679/0024/d-ext-0001?cmpid=70140000000cFJb', $post_data, 'POST');


	$url = 'http://info.liveops.com/acton/eform/14679/0024/d-ext-0001?cmpid=70140000000cFJb';
	$data = $post_data;
	$options = array(
			'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($data),
			)
	);

	$context  = stream_context_create($options);
	$acton_response = file_get_contents($url, false, $context);



/**/
	// If there's an error posting the data to Eloqua, add relevant debugging info to the log
	// and also save the form data so it can be reposted to Eloqua.

    // ActOn seems to behave differently let's change the response check step
    //10/23/2015 - the acton_response is now a string
	//if(rtrim($acton_response['curl_result']) == '') //no response from curl, then error?? //Now a string
	if ($acton_response=='')
	{
		error_log("ActOn Submission Failure: " . $acton_response['curl_error']);
		error_log("Errno: " . $acton_response['curl_errno']);
		error_log(serialize($post_data));
		error_log(serialize($acton_response['curl_getinfo']));
		echo '{"ap_val":' . $ap_val . ', "aa_val":' . $aa_val . ', "cl_val":' . $cl_val . ', "email_sent": false}';
	}
	else
	{
		echo '{"ap_val":' . $ap_val . ', "aa_val":' . $aa_val . ', "cl_val":' . $cl_val . ', "email_sent": true}';
	}
/**/

}
