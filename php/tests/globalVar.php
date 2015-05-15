<?php
class GlobalVar
{
  const KEY = 'PLACE_YOUR_KEY_HERE';
  const SECRET = 'PLACE_YOUR_SECRET_HERE';

  public static function getKey() {
    return self::KEY;
  }

  public static function getSecret() {
    return self::SECRET;
  }
}

?>