<?php

# Arvin Castro, arvin@sudocode.net
# 27 June 2011
# http://sudocode.net/sources/includes/class-clientlogin-php/

class clientlogin {

   const alerts = 'alerts';
   const analytics = 'analytics';
   const blogger = 'blogger';
   const calendar = 'cl';
   const contacts = 'cp';
   const customsearch = 'cprose';
   const documents = 'writely';
   const friendconnect = 'peoplewise';
   const insights = 'trendspro';
   const groups = 'groups2';
   const latitude = 'friendview';
   const mail = 'mail';
   const maps = 'local';
   const music = 'sj';
   const picasawebalbums = 'lh2';
   const reader = 'reader';
   const trends = 'trends';
   const urlshortener = 'urlshortener';
   const voice = 'grandcentral';
   const webmastertools = 'sitemaps';
   const youtube = 'youtube';

   public function __construct($email, $password, $service, $accountType = 'HOSTED_OR_GOOGLE') {

      $options = array('http' =>
          array(
            'ignore_errors' => true,
              'method'  => 'POST',
              'header'  => 'Content-type: application/x-www-form-urlencoded',
              'content' => http_build_query(array(
                 'accountType' => $accountType,
                 'Email'       => $email,
                 'Passwd'      => $password,
                 'service'     => $service,
                 'source'      => 'cambiata'
            ))
          )
      );
      $context = stream_context_create($options);
      $this->response = file_get_contents('https://www.google.com/accounts/ClientLogin', false, $context);

      if(false !== strpos($http_response_header[0], '200')) {
         foreach(explode("\n", $this->response) as $line) {
            list($key, $value) = explode('=', $line, 2);
            if($key) $this->{strtolower($key)} = $value;
         }
      } else {
         $this->error = ($this->response) ? $this->response: $http_response_header[0];
      }
   }

   public function toAuthorizationHeader() {
      return "GoogleLogin auth={$this->auth}";
   }
}

?>