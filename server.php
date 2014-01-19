<?php
    class Server  {
        
        private $url;
        private $username;
        private $password;
     //   private $project;
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
        
        function get_project_list()
        {
            return $this->my_json_decode('/getprojectlist.rest');
        }
        
        function get_category_list()
        {
            return $this->my_json_decode('/getcategorylist.rest');
        }

        function create_task($taskname, $projectid, $group, $category, $details)
        {
            
            $details2 = str_replace ("\r\n", "\\r\\n", $details);
            $details2 = str_replace ("\r", "\\r\\n", $details2);
            $details2 = str_replace ("\n", "\\r\\n", $details2);
     
            return $this->my_json_decode('/createtask.rest',
                                         "projectid=$projectid\r\nassigntoname=$group\r\ncategory=$category\r\ntitle=$taskname\r\ndetails=$details2\r\n");
        }
        
        function my_json_decode($cmd,$data='')
        {
            $result = $this->do_post_request($cmd,$data);
            $decoded = json_decode(str_replace('""level','","level',$result)); //str_replace = workaround for json bug
    
            if (count($decoded) >= 1)
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
                                       //      'header' => "Content-Type: application/x-rstaskgroup-name-value-pair; charset=UTF-8"
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