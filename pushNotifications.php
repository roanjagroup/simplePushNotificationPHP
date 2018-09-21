<?php
        /* We are using the sandbox version of the APNS for development. For production
        environments, change this to ssl://gateway.push.apple.com:2195 */
        $apnsServer = 'tls://gateway.sandbox.push.apple.com:2195';
        /* Make sure this is set to the password that you set for your private key
        when you exported it to the .pem file using openssl on your OS X */
        $privateKeyPassword = '';
        /* Put your own message here if you want to */
        $message = 'prueba Drupal MIP';
        /* Pur your device token here */
        $deviceToken =
        '3E3A4846D1D6C6BE13A3A95D54FE4AA3FD4CE90C3D6D18C78EE42DD7B487D49B';
        /* Replace this with the name of the file that you have placed by your PHP
        script file, containing your private key and certificate that you generated
        earlier */
        $pushCertAndKeyPemFile = 'server_certificates_bundle_sandbox.pem';
        $stream = stream_context_create();
        stream_context_set_option($stream, 'ssl', 'passphrase',  $privateKeyPassword);
        stream_context_set_option($stream,'ssl','local_cert', $pushCertAndKeyPemFile);

        $connectionTimeout = 20;

        $connectionType = STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT;

        $connection = stream_socket_client($apnsServer,
        $errorNumber,
        $errorString,
        $connectionTimeout,
        $connectionType,
        $stream);
        if (!$connection){
        echo "Failed to connect to the APNS server. Error no = $errorNumber<br/>";
        exit;
        } else {
        echo "Successfully connected to the APNS. Processing...</br>";
        }
        $messageBody['aps'] = array('alert' => $message,
        'sound' => 'default',
        'badge' => 2,
        );
        $payload = json_encode($messageBody);
        $notification = chr(0) .
        pack('n', 32) .
        pack('H*', $deviceToken) .
        pack('n', strlen($payload)) .
        $payload;

        var_dump($notification);

        $wroteSuccessfully = fwrite($connection, $notification, strlen($notification));
        if (!$wroteSuccessfully){
        echo "Could not send the message<br/>";
        }
        else {
        echo "Successfully sent the message<br/>";
        }
        fclose($connection);

  ?>