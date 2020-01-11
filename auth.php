<?php
  require 'vendor/autoload.php';
  use Ramsey\Uuid\Uuid;
  use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
  
  try {
    // Generate a version 4 (random) UUID object
    $uuid4 = Uuid::uuid4();
  } catch (UnsatisfiedDependencyException $e) {
    // Some dependency was not met. Either the method cannot be called on a
    // 32-bit system, or it can, but it relies on Moontoast\Math to be present.
    echo 'Caught exception: ' . $e->getMessage() . "\n";
  }

  $imagekitapi = "private_F3MhKgkEY03k7AjnwWew6YBLTe8";

  $token = $uuid4->toString();
  $expire = time() + (60 * 60);
  $signature = hash_hmac('sha1', $token . $expire, $imagekitapi);

  $auth = new \stdClass();
  $auth->token = $token;
  $auth->expire = $expire;
  $auth->signature = $signature;
  $authJSON = json_encode($auth);
  var_dump($authJSON);
?>