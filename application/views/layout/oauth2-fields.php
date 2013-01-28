<input type="hidden" name="response_type" value="code"/>
<input type="hidden" name="client_id" value="<?php echo INGRESSMX_GAPI_CLIENT_ID ?>"/>
<input type="hidden" name="redirect_uri" value="<?php echo INGRESSMX_GAPI_REDIRECT_URI ?>"/>
<input type="hidden" name="scope" value="<?php echo INGRESSMX_GAPI_SCOPE ?>"/>
<input type="hidden" name="state" value="<?php
  echo $this->QuarkURL->getURL(
    $this->QuarkURL->getPathInfo()->controller
    .'/'.$this->QuarkURL->getPathInfo()->action
    .'/'.implode('/', $this->QuarkURL->getPathInfo()->arguments)
  );
  $query_string = http_build_query($_GET);
  if ($query_string != '') {
    echo '?'.$query_string;
  }
?>"/>
