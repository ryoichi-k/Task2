
<?php
function getToken()
{
  return hash('sha256', session_id());
}