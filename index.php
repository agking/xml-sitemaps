<?php

/**
 * @param SimpleXMLElement $obj
 * @return array
 */
function xmlObjToArr(SimpleXMLElement $obj) {
    $namespace = $obj->getDocNamespaces(true);
    $namespace[NULL] = NULL;

    $children = array();
    $attributes = array();
    $name = strtolower((string)$obj->getName());

    $text = trim((string)$obj);
    if( strlen($text) <= 0 ) {
        $text = NULL;
    }

    // get info for all namespaces
    if(is_object($obj)) {
        foreach( $namespace as $ns=>$nsUrl ) {
            // attributes
            $objAttributes = $obj->attributes($ns, true);
            foreach( $objAttributes as $attributeName => $attributeValue ) {
                $attribName = strtolower(trim((string)$attributeName));
                $attribVal = trim((string)$attributeValue);
                if (!empty($ns)) {
                    $attribName = $ns . ':' . $attribName;
                }
                $attributes[$attribName] = $attribVal;
            }

            // children
            $objChildren = $obj->children($ns, true);
            foreach( $objChildren as $childName=>$child ) {
                $childName = strtolower((string)$childName);
                if( !empty($ns) ) {
                    $childName = $ns.':'.$childName;
                }
                $children[$childName][] = xmlObjToArr($child);
            }
        }
    }

    return array(
        'name'=>$name,
        'text'=>$text,
        'attributes'=>$attributes,
        'children'=>$children
    );
}


$sitemap_url = 'http://sitemaps.hostingbackend.net/www_bwin_com.php?s.xml';

$sitemap_file = file_get_contents($sitemap_url);
$xml = simplexml_load_string($sitemap_file);

//$ns = $xml->getNamespaces(true);
//$url = $xml->url[0];
//var_dump($xml);
//$objAttributes = $xml->attributes($ns, true);

$sitemap_array = xmlObjToArr($xml);

var_dump($sitemap_array);
