<?php
error_reporting(E_ERROR | E_PARSE | E_WARNING);

$user = new user;
$user->apikey = ""; // put your API key here
$user->domain = "localhost"; // put your domain


class user
{
    public static $apikey;
    public static $domain;

    public function GetPlayerSummaries ($steamid)
    {
        $response = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $this->apikey . '&steamids=' . $steamid);
        $json = json_decode($response);
        return $json->response->players[0];
    }

    public function signIn ()
    {
        require_once 'openid.php';
        $openid = new LightOpenID($this->domain);// put your domain
        if(!$openid->mode)
        {
            $openid->identity = 'http://steamcommunity.com/openid';
            header('Location: ' . $openid->authUrl());
        }
        elseif($openid->mode == 'cancel')
        {
            print ('User has canceled authentication!');
        }
        else
        {
            if($openid->validate())
            {
                preg_match("/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/", $openid->identity, $matches); // steamID: $matches[1]
                setcookie('steamID', $matches[1], time()+(60*60*24*7), '/'); // 1 week
                header('Location: /');
                exit;
            }
            else
            {
                print ('fail');
            }
        }
    }
}

if(isset($_GET['login']))
{
    $user->signIn();
}
if (array_key_exists( 'logout', $_POST ))
{
    setcookie('steamID', '', -1, '/');
    header('Location: /');
}


if(!$_COOKIE['steamID'])
{
    print ('<form action="?login" method="post">
        <input type="image" src="http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_large_border.png"/>
        </form>');
}
else
{
    print('<form method="post"><button title="Logout" name="logout">Logout</button></form>');
    echo $user->GetPlayerSummaries($_COOKIE['steamID'])->personaname;
}