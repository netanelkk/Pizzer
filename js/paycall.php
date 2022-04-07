<?php   
    ##############################################################
    # Name: class.micropayment.php                               #
    # File Description: Micropayment Class                       #
    # Author: Paycall                                            #
    # Web: http://www.paycall.co.il/                             #
    # Update: 2012-12-06                                         #
    #                                                            #
    # Discription:                                               #
    # The micropayment is PayCall official API object.           #
    # This object purpose is to ease the integration of the      #
    # PayCall system whitout directly accessing the API.         #
    # The object handles any API request to the server.          #
    #                                                            #
    # NOTE: Changing the object can cause malfunctions and       #
    # disruptions in the clearing process.                       #
    ##############################################################
   
    define("WS_POST", 0);
    define("WS_GET" , 1);

    class micropayment {
        # Do not make any changes here
        static private $sessonID        = "paycall-sesson-identifier";
        static private $server          = "http://admin.paycall.co.il/ws.php"; # paycall server
       
        static function getPremiumPhone($Price, $Language = CALL_LAN::HE){
            if(!is_numeric($Price) || $Price < 0 || 100 < $Price) 
                throw new Exception('Price id invalid');

            if(isset($_SESSION[self::$sessonID][$Price][$Language])){
                //check if the phone number is in the session 
                return $_SESSION[self::$sessonID][$Price][$Language];
            }else{
                $queryString = http_build_query(array("service" => "initPn",
                                                      "price" => $Price, 
                                                      "lan" => $Language,
                                                 ));
                $tmpxml = self::Get_URL(self::$server, WS_POST, $queryString);        

                if(self::getvaluebykey($tmpxml, "STATUS") == 'ok'){
                    return $_SESSION[self::$sessonID][$Price][$Language] = self::getvaluebykey($tmpxml, "DDIVISUAL");   
                }else{
                    return NULL; 
                }
            }     
        }
        
        //check if the transaction is done and close it 
        static function collection($BusinessCode,$Price,$PremiumPhone,$CallerPhone,$note = '')
        {
            $queryString = http_build_query(array(
                                                "service" => "collectPn",
                                                "price" => $Price, 
                                                "ddi" => str_replace("-","",$PremiumPhone),
                                                "custId" => $BusinessCode,
                                                "cli" => $CallerPhone,
                                                "note" =>  urldecode($note),
                                                "cip" => (empty($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['REMOTE_ADDR']:(is_array($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR'][0]:$_SERVER['HTTP_X_FORWARDED_FOR'])),
                                             ));
            $tmpxml= self::Get_URL(self::$server, WS_POST ,$queryString);

            if(self::getvaluebykey($tmpxml, "CHARGE_STATUS") == 'true'){
                return array(
                    "SERVICE"           => self::getvaluebykey($tmpxml,"SERVICE"),
                    "STATUS"            => self::getvaluebykey($tmpxml,"STATUS"),     
                    "CHARGE_STATUS"     => self::getvaluebykey($tmpxml,"CHARGE_STATUS"),
                    "REQUEST_TIME"      => self::getvaluebykey($tmpxml,"REQUEST_TIME"),
                    "UNIQUE_ID"         => self::getvaluebykey($tmpxml,"UNIQUE_ID"),
                    "ADD_IN_TIME"       => self::getvaluebykey($tmpxml,"ADD_IN_TIME"),
                    "DDI"               => self::getvaluebykey($tmpxml,"DDI"),     
                    "CLI"               => self::getvaluebykey($tmpxml,"CLI"),
                    "CODE"              => self::getvaluebykey($tmpxml,"CODE"),     
                    "PAY_IN"            => self::getvaluebykey($tmpxml,"PAY_IN"),                  
                );
            }
            return false; 
        }#-#collection()
        
        static function clearHistory(){
            unset($_SESSION[self::$sessonID]);
        }
        
        //check if the transaction is done without closing it
        static function checkTransaction($BusinessCode,$Price,$PremiumPhone,$CallerPhone){
            $queryString = http_build_query(array(
                                                "service" => "collectPn",
                                                "price" => $Price, 
                                                "ddi" => str_replace("-","",$PremiumPhone),
                                                "custId" => $BusinessCode,
                                                "cli" => $CallerPhone,
                                                "note" =>  "",
                                                "cip" => (empty($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['REMOTE_ADDR']:(is_array($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR'][0]:$_SERVER['HTTP_X_FORWARDED_FOR'])),
                                                "tsmp" => rand(),
                                             ));
            $tmpxml=self::Get_URL(self::$server, WS_POST, $queryString);
            if(self::getvaluebykey($tmpxml, "CHARGE_STATUS") == 'true'){
                return array(
                    "SERVICE"           => self::getvaluebykey($tmpxml,"SERVICE"),
                    "STATUS"            => self::getvaluebykey($tmpxml,"STATUS"),     
                    "CHARGE_STATUS"     => self::getvaluebykey($tmpxml,"CHARGE_STATUS"),
                    "REQUEST_TIME"      => self::getvaluebykey($tmpxml,"REQUEST_TIME"),
                    "UNIQUE_ID"         => self::getvaluebykey($tmpxml,"UNIQUE_ID"),
                    "ADD_IN_TIME"       => self::getvaluebykey($tmpxml,"ADD_IN_TIME"),
                    "DDI"               => self::getvaluebykey($tmpxml,"DDI"),     
                    "CLI"               => self::getvaluebykey($tmpxml,"CLI"),
                    "CODE"              => self::getvaluebykey($tmpxml,"CODE"),     
                    "PAY_IN"            => self::getvaluebykey($tmpxml,"PAY_IN"),
                );    
            }
            return false;
        }
        

        // Get_URL open http request the connection using POST or GET 
        static function Get_URL($url, $wsMethod=WS_GET ,$argQuery = "")
        {
             if($wsMethod == WS_GET){
                if(strpos($url,"?") !== false) 
                    $url = $url."&".$argQuery;
                else
                    $url = $url."?".$argQuery; 
            } 
                   
            if (function_exists('curl_init')==true){
                
                $ch = curl_init() or $this->error=curl_error();
                curl_setopt($ch, CURLOPT_URL,$url);
                if($wsMethod==WS_POST){ //use POST method
                    curl_setopt($ch, CURLOPT_POST,1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$argQuery);
                }
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $data=curl_exec($ch);
                curl_close($ch);
            }else{
                if($wsMethod==WS_GET){  //use GET method
                    $file = fopen ($url, "r");
                    if(!$file){
                        $this->error="Unable to open remote file.";
                        exit;
                    }
                    while(!feof($file))
                        $data .= fgets($file, 64);
                
                    fclose($file);
                }else{ //use POST method
                    $opts = array('http' =>
                        array(
                            'method'  => 'POST',
                            'header'  => 'Content-type: application/x-www-form-urlencoded',
                            'content' => $argQuery
                            )
                        ); 
                    $context = stream_context_create($opts);
                    $file = fopen ($url, "r",false,$context);
                    if(!$file){
                        $this->error="Unable to open remote file.";
                        exit;
                    }
                    while(!feof($file))
                        $data .= fgets($file, 64);
                   
                    fclose($file);       
                }
            }  
            return $data; 
        }#-#Get_URL()

        // $this->getvaluebykey base xml parser 
        static function getvaluebykey($string, $key)
        {
            $start="<$key>";
            $end="</$key>";
            $string = " ".$string;
            $ini = strpos($string,$start);
            if ($ini == 0) return "";
            $ini += strlen($start);
            $len = strpos($string,$end,$ini) - $ini;
            return substr($string,$ini,$len);
        }#-#$this->getvaluebykey()    
    
    
    }

    
    class CALL_LAN
    {
        const HE = 0;
        const EN = 1;
        const RU = 2;
        const AR = 3;
    }

?>
