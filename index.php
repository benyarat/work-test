
<?php
class txtFunds{
    
    private $user       = 'apitest@ipayoptions.com';
    private $pass       = '1q2w3e4r5t6y';
    private $url        = 'http://txfunds.uat.ipayoptions.com/api.php';
    public  $loginId    = '';
    
    /**
     * Function that login user and store user's loginid
     * 
     * @return boolean 
     */
    public function login() 
    {
        $request['act']      = 'login' ;
        $request['email']    = $this->user; 
        $request['password'] = $this->pass;
        
        $ch                           = curl_init($this->url); 
        $opts[CURLOPT_POST]           = true; 
        $opts[CURLOPT_POSTFIELDS]     = http_build_query($request); 
        $opts[CURLOPT_RETURNTRANSFER] = true; 
        curl_setopt_array($ch,$opts);
        $jsonResponse  = curl_exec($ch);
        $arrResponse = json_decode($jsonResponse, 1);
        
        if($arrResponse['status']===true)
        {
            $this->loginId = $arrResponse['data']['loginid'];
            return true;
        }
        else
            return false;
    }
    
    /**
     * Fucntion that check if user is exist or not
     * @param array $recipient
     * @return json 
     */
    public function checkUser($recipient)
    {
        $request['act']         = 'usercheck';
        $request['loginid']     = $this->loginId;
        $request['email']       = $recipient['email'];
        $request['mobile']      = $recipient['mobile'];
        $request['country']     = $recipient['country'];
        
        $ch                             = curl_init($this->url); 
        $opts[CURLOPT_POST]             = true; 
        $opts[CURLOPT_POSTFIELDS]       = http_build_query($request); 
        $opts[CURLOPT_RETURNTRANSFER]   = true; 
        curl_setopt_array($ch,$opts);
        $jsonResponse  = curl_exec($ch);
        $arrResponse = json_decode($jsonResponse, 1);
        
        return $arrResponse;
    }
    
}


//requires parames for recipient
$recipient    = array(
    'email'     =>  'test@ipayoptions.com',
    'mobile'    =>  '1221212121112',
    'country'   =>  'AU'
);

$obj = new txtFunds();
$userStatus = $obj->login();
if($userStatus===true)
{
    $recipientInfo = $obj->checkUser($recipient);
    if($recipientInfo['status']===true)
    {
        $data = $recipientInfo['data'];
        $user = '
        <table border="1" cellpadding="5" width="300">
                <tr>
                        <td>User Id</td>
                        <td>E-mail</td>
                        <td>Status</td>
                </tr>
                <tr>
                        <td>'.$data['userid'].'</td>
                        <td>'.$recipient['email'].'</td>
                        <td>'.$data['status'].'</td>
                </tr>
        </table>';

        $accountids = '';
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
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            echo $user;
            echo $accountids;
        ?>
    </body>
</html>
