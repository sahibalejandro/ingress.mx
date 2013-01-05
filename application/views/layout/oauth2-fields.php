<input type="hidden" name="response_type" value="code"/>
<input type="hidden" name="client_id" value="<?php echo GOOGLE_API_CLIENT_ID ?>"/>
<input type="hidden" name="redirect_uri" value="<?php echo GOOGLE_API_REDIRECT_URI ?>"/>
<input type="hidden" name="scope" value="<?php echo GOOGLE_API_SCOPE ?>"/>
<input type="hidden" name="state" value="<?php echo $this->QuarkURL->getURLToSwitchLang(null); ?>"/>
