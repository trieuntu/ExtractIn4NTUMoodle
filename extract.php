<?php

function extractUserAndEmail($htmlPath) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTMLFile($htmlPath);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    $rows = $xpath->query('//table[@id="submissions"]/tbody/tr');

    $result = [];

    foreach ($rows as $row) {
        $nameNode = $xpath->query('.//td[contains(@class, "c1 username")]/a', $row)->item(0);
        $usernameNode = $xpath->query('.//td[contains(@class, "c2 username")]', $row)->item(0);
        $emailNode = $xpath->query('.//td[contains(@class, "c3 email")]', $row)->item(0);

        if ($nameNode && $usernameNode && $emailNode) {
            $fullname = trim($nameNode->nodeValue);
            $username = trim($usernameNode->nodeValue);
            $email = trim($emailNode->nodeValue);

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result[] = [$username, $fullname, $email];
            }
        }
    }

    return $result;
}
