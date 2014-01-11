<?php
    class Server  {
        
        private $url;
        private $username;
        private $password;
        
        function __construct($url, $username, $password)
        {
            $this->url=$url;
            $this->username=$username;
            $this->password=$password;
        }
        
        function login()
        {
            $result = $this->get_group_list();
            $decoded = json_decode($result);

            if (count($decoded) == 1)
            {
                if ($decoded[0]->{"result"})
                {
                    return 0;
                }
                else
                {
                    $errorinfo = $decoded[0]->{"errorinfo"};
                    switch ($errorinfo)
                    {
                        case "Error: login failed":
                            return "Login am Swissistent Tasks Server ist fehlgeschlagen, bitte pr&uuml;fen Sie Benutzernamen und Passwort";
                    }
                    return $errorinfo;
                }
            }
            else
            {
                return "Fehler: Bitte pr&uuml;fen Sie Benutzernamen und Passwort";
            }
        }
        
        function get_group_list()
        {
            $data = "loginuser=$this->username\r\nloginpass=$this->password\r\n";
            $result = $this->do_post_request("/getgrouplist.rest", $data);
            return $result;
        }
    
        function do_post_request($cmd, $data)
        {
            $params = array('http' => array(
                                        'method' => 'POST',
                                        'content' => $data,
                                        'header' => "Content-Type: application/x-rstaskgroup-name-value-pair; charset=UTF-8\r\nConnection: close"
                                            ));
            $ctx = stream_context_create($params);

            $fp = fopen($this->url.$cmd, 'rb', false, $ctx);
            if (!$fp)
            {
                throw new Exception("Problem with $url");
            }
            $response = @stream_get_contents($fp);
            if ($response === false)
            {
                throw new Exception("Problem reading data from $url");
            }
            return $response;
        }
    }
?>