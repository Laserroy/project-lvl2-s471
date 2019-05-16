<?php
namespace DiffGenerator\DifferenceFormatter;

function boolToString($value)
{
    if ($value === true) {
        return "true";
    }
    if ($value === false) {
        return "false";
    }
    return $value;
}

function renderDiff($diffTree, $offset = "")
{
    $result = array_reduce(
        $diffTree,
        function ($acc, $current) use ($offset) {
            if ($current["children"] === "nested") {
                $newOffset = $offset . "    ";
                $resultString = renderDiff($current["value"], $newOffset);
                $acc .= $offset . "  " . $current["status"] . " " . $current["name"] . ": " . $resultString;
                return $acc;
            }
            if (is_array($current["value"])) {
                $newOffset = empty($offset) ? "        " : $offset . "    ";
                $resultString = '';
                foreach ($current["value"] as $k => $v) {
                    $resultString .= $newOffset . $offset . $k . ": " . boolToString($v) . "\n";
                }
                $acc .= "{$offset}  {$current["status"]} {$current["name"]}: {\n" . $resultString . $offset . "    }\n";
                return $acc;
            } else {
                $acc .= "{$offset}  {$current["status"]} {$current["name"]}: " . boolToString($current["value"]) . "\n";
                return $acc;
            }
        },
        ""
    );
    $result2 = "{\n" . $result . $offset . "}\n";
    return $result2;
}

function buildMessage($node1, $node2 = [], $prevNames = [])
{
    if (empty($node2)) {
        ["name" => $name, "value" => $value, "status" => $status] = $node1;
        $prevNames[] = $name;
        $nameForMessage = implode(".", $prevNames);
        $valueForMessage = is_array($value) ? "complex data" : $value;
        $action = $status === "+" ? "was added with value: '$valueForMessage'" : "was removed";
        $message = "Property '$nameForMessage' $action";
        return $message;
    } else {
        $prevNames[] = $node1["name"];
        $nameForMessage = implode(".", $prevNames);
        $beforeValue = $node2["value"];
        $afterValue = $node1["value"];
        $message = "Property '$nameForMessage' was changed. From '$beforeValue' to '$afterValue'";
        return $message;
    }
}

function renderPlainDiff($diffTree, $prevNames = [])
{
    $plainDiff = array_reduce($diffTree, function ($acc, $node) use ($prevNames, $diffTree) {
        if ($node["children"] === "nested") {
            $prevNames[] = $node["name"];
            $acc[] = renderPlainDiff($node["value"], $prevNames);
            return $acc;
        } else {
            if ($node["status"] === "+") {
                $currentNodeName = $node["name"];
                $oppositeNodes = array_filter($diffTree, function ($children) use ($currentNodeName) {
                    return $children["name"] === $currentNodeName;
                });
                if (count($oppositeNodes) > 1) {
                    [$node1, $node2] = array_values($oppositeNodes);
                    $acc[] = buildMessage($node1, $node2, $prevNames);
                    return $acc;
                }
                $acc[] = buildMessage($node, [], $prevNames);
                return $acc;
            }
            if ($node["status"] === "-") {
                $oppositeNodes = array_filter($diffTree, function ($children) use ($node) {
                    return $children["name"] === $node["name"];
                });
                if (count($oppositeNodes) < 2) {
                    $acc[] = buildMessage($node, [], $prevNames);
                    return $acc;
                }
            }
            return $acc;
        }
    }, []);
    $result = implode("\n", $plainDiff);
    return $result;
}
