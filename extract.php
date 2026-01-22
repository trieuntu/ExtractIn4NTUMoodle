<?php

function extractUsernamesFromHtml($htmlPath) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTMLFile($htmlPath);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $rows = $xpath->query('//table[@id="submissions"]/tbody/tr');

    $usernames = [];

    foreach ($rows as $row) {
        $usernameNode = $xpath->query('.//td[contains(@class, "c2 username")]', $row)->item(0);
        if ($usernameNode) {
            $username = trim($usernameNode->nodeValue);
            if ($username !== '') {
                $usernames[] = $username;
            }
        }
    }

    return array_values(array_unique($usernames));
}
