<?php
    class Server  {
        
        private $url;
        private $username;
        private $password;
        private $baseString;
        
        function __construct($url, $username, $password)
        {
            $this->url = $url;
            $this->username = $username;
            $this->password = $password;
            $this->baseString = "loginuser=$this->username\r\nloginpass=$this->password\r\n";
        }
        
        function login()
        {
            return $this->my_json_decode('/getgrouplist.rest');
        }

        function get_group_list()
        {
            return $this->my_json_decode('/getgrouplist.rest');
        }
        
        function create_task($taskname, $details)
        {
            
            $details2 = str_replace ("\r\n", "\\r\\n", $details);
            $details2 = str_replace ("\r", "\\r\\n", $details2);
            $details2 = str_replace ("\n", "\\r\\n", $details2);
     
            return $this->my_json_decode('/createtask.rest',"projectid=8\r\ntitle=$taskname\r\ndetails=$details2\r\n");
        }
        
        function my_json_decode($cmd,$data='')
        {
            $result = $this->do_post_request($cmd,$data);

            $decoded = json_decode($result);

            if (count($decoded) == 1)
            {
                if (!$decoded[0]->{"result"})
                {
                    $errorinfo = $decoded[0]->{"errorinfo"};
                    switch ($errorinfo)
                    {
                        case "Error: login failed":
                            $decoded[0]->{"errorinfo"} = "Login am Swissistent Tasks Server ist fehlgeschlagen, bitte pr&uuml;fen Sie Benutzernamen und Passwort";
                            break;
                    }
                }
            }
            else
            {
                $decoded = array(array('result' => false, 'errorinfo' => "Fehler: Bitte pr&uuml;fen Sie Benutzernamen und Passwort"));
            }
            
            return $decoded;
        }
    
        function do_post_request($cmd, $data)
        {
            $params = array('http' => array(
                                        'method' => 'POST',
                                        'content' => $this->baseString.$data,
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