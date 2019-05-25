<?php
namespace Differ\DiffFormatter;

function buildMessage($node1, $node2 = [], $prevNames = [])
{
    if (empty($node2)) {
        ["name" => $name, "value" => $value, "status" => $status] = $node1;
        $prevNames[] = $name;
        $nameForMessage = implode(".", $prevNames);
        $valueForMessage = is_object($value) ? "complex value" : $value;
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

function makePlainDiff($diffTree, $prevNames = [])
{
    $plainDiff = array_reduce($diffTree, function ($acc, $node) use ($prevNames, $diffTree) {
        if ($node["children"] === "nested") {
            $prevNames[] = $node["name"];
            $acc[] = makePlainDiff($node["value"], $prevNames);
            return $acc;
        } else {
            if ($node["status"] === "+") {
                $currentNodeName = $node["name"];
                $oppositeNodes = array_filter($diffTree, function ($node) use ($currentNodeName) {
                    return $node["name"] === $currentNodeName;
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
    return implode("\n", $plainDiff);
}
