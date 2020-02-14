<?php
require(__DIR__ . "/src/html_parser_utils.php");
require(__DIR__ . "/src/html_tag_insight.php");

error_reporting(E_ERROR | E_PARSE);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, OPTIONS');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 3153600');


function main($url)
{
    $content = file_get_contents($url, false);
    $html_tag_string_chunks = HtmlParserUtils::getHtmlHeadString($content);

    list(
        $TAG_ATTRS_HASHMAP,
        $TAG_NAME_HASHMAP,
        $CLASS_HASHMAP,
        $SRC_HASHMAP,
        $HREF_HASHMAP,
        $TAG_ATTR_VAL_DISTINCT_HASHMAP,
    ) = parseHtmlChunks($html_tag_string_chunks);

    $class_hashmap = [];
    foreach ($CLASS_HASHMAP as $attr => $val) {
        $class_hashmap[] = [
            "tag" => $attr,
            "count" => $val,
        ];
    }

    $tag_attrs_hashmap = [];
    foreach ($TAG_ATTRS_HASHMAP as $attr => $attr_list) {
        $children = [];
        foreach ($attr_list as $class => $attr_val) {
            # filter most of tiny attributes;
            if (count($attr_val) < 1)  {
                continue;
            }

            // Show attr values
            $children_vals = [];
            foreach ($attr_val as $val) {
                $children_vals[] = [
                    "name" => $val,
                    "value" => 1,
                ];
            }

            $children[] = [
                "name" => $class,
                "value" => count($attr_val),
                "children" => $children_vals
            ];
        }
        $tag_attrs_hashmap[] = [
            "name" => $attr,
            "children" => $children,
        ];
    }
    return [
        "tag_attr_mapping" => $tag_attrs_hashmap,
        "tag_name_mapping" => $TAG_NAME_HASHMAP,
        "class_mapping" => $class_hashmap,
        "src_mapping" => $SRC_HASHMAP,
        "href_mapping" => $HREF_HASHMAP,
        "tag_attr_val_distinct_mapping" => $TAG_ATTR_VAL_DISTINCT_HASHMAP,
    ];
}


$url = json_decode(file_get_contents('php://input'), true);
$result = main($url);

echo json_encode($result);
