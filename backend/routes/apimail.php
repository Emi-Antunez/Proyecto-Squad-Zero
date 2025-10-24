<?php

$resend = Resend::client('••••••••••••••••••••••••••••••••••••');

$resend->emails->send([
  'from' => 'onboarding@resend.dev',
  'to' => 'squadzero1234@gmail.com',
  'subject' => 'Hello World',
  'html' => '<p>Congrats on sending your <strong>first email</strong>!</p>'
]);