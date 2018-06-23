<?php

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Get the form fields and remove whitespace.
        $img = trim($_POST["data"]);

        // // Check that data was sent to the mailer.
        // if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //     // Set a 400 (bad request) response code and exit.
        //     http_response_code(400);
        //     echo "Oops! There was a problem with your submission. Please complete the form and try again.";
        //     exit;
        // }

        // Replace <Subscription Key> with a valid subscription key.
        $ocpApimSubscriptionKey = 'xxxxxxxx';

        // You must use the same location in your REST call as you used to obtain
        // your subscription keys. For example, if you obtained your subscription keys
        // from westus, replace "westcentralus" in the URL below with "westus".
        $uriBase = 'https://brazilsouth.api.cognitive.microsoft.com/vision/v1.0/describe?maxCandidates=1s';

        $imageUrl = $img;

        require_once 'HTTP/Request2.php';

        $request = new Http_Request2($uriBase . '/analyze');
        $url = $request->getUrl();

        $headers = array(
            // Request headers
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $ocpApimSubscriptionKey
        );
        $request->setHeader($headers);

        $parameters = array(
            // Request parameters
            'visualFeatures' => 'Categories,Description',
            'details' => '',
            'language' => 'es'
        );
        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_POST);

        // Request body parameters
        $body = json_encode(array('url' => $imageUrl));

        // Request body
        $request->setBody($body);

        try
        {
            $response = $request->send();
            echo $response->getBody();
        }
        catch (HttpException $ex)
        {
            echo "<pre>" . $ex . "</pre>";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>
