        include('class.smtp.inc');

		$params = array(
						'host' => '10.1.1.2',		// Mail server address
						'port' => 25,				// Mail server port
						'helo' => 'example.com',	// Use your domain here.
						'auth' => FALSE,			// Whether to use authentication or not.
						'user' => '',				// Authentication username
						'pass' => ''				// Authentication password
					   );

        $smtp =& smtp::connect($params);

		$send_params = array(
							'from'			=> 'joe@example.com',			// The return path
							'recipients'	=> 'richard@[10.1.1.2]',		// Can be more than one address in this array.
							'headers'		=> array(
														'From: "Joe" <joe@example.com>',
														'To: "Richard Heyes" <richard@[10.1.1.2]>',	// A To: header is necessary, but does
														'Subject: Test email'							// not have to match the recipients list.
													)
					 	    );
        $mail->smtp_send($smtp, $send_params);