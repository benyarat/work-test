<?php
$request['act'] = 'login';
$request['email'] = 'apitest@ipayoptions.com';
$request['password'] = '1q2w3e4r5t6y';

$ch = curl_init('http://txfunds.uat.ipayoptions.com/api.php');
$opts[CURLOPT_POST] = true;
$opts[CURLOPT_POSTFIELDS] = http_build_query($request);
$opts[CURLOPT_RETURNTRANSFER] = true;
curl_setopt_array($ch,$opts);
$response = curl_exec($ch);
$response = json_decode($response,1);

if($response['status']==1)
{
	$recipient = 'test@ipayoptions.com';

	$request['act'] = 'usercheck' ;
	$request['mobile'] = '1221212121112';
	$request['country'] = 'AU';
	$request['loginid'] = $response['data']['loginid'];
	$request['email'] = $recipient;

	unset($response);

	$ch = curl_init('http://txfunds.uat.ipayoptions.com/api.php');
	$opts[CURLOPT_POST] = true;
	$opts[CURLOPT_POSTFIELDS] = http_build_query($request);
	$opts[CURLOPT_RETURNTRANSFER] = true;
	curl_setopt_array($ch,$opts);
	$response = curl_exec($ch);
	$response = json_decode($response,1);

	if($response['status']==1)
	{
		$data = $response['data'];
		$user = '
		<table border="1" cellpadding="5" width="300">
			<tr>
				<td>E-mail</td>
				<td>User Id</td>
				<td>Status</td>
			</tr>
			<tr>
				<td>'.$recipient.'</td>
				<td>'.$data['userid'].'</td>
				<td>'.$data['status'].'</td>
			</tr>
		</table>';

		$accountids = "";
		if(is_array($data['accountid']) && count($data['accountid'])>0)
		{
			$accountids .= '
			<br/>
			<table border="1" cellpadding="5" width="300">
				<tr>
					<td>Account Id</td>
				</tr>';

			foreach ($data['accountid'] as $key => $value) 
			{
				$accountids .= '
					<tr>
						<td>'.$value.'</td>
					</tr>';
			}

			$accountids .= '</table>'; 
		}

		echo $user;
		echo $accountids;
	}
}
?>
