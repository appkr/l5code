#! /usr/bin/env php

<?php

$data = [
    '3b4f42b508153a84fbbef673de7b1553fc1f690c' =>  '4036-final-touch2',
    '7285ea04245586256a379e1141e5e2174f672656' =>  '4035-final-touch1',
    'f83bb116810ef70a672de69a4a668a0d5934a916' =>  '4034-transform',
    '0709092f66334343be0ad513c58c7ff4f472aa20' =>  '4033-authentication',
    '87cc86e284599f90ba3dc1cb209e2098c4490427' =>  '4032-structuring',
    '77d9605e77a51b8cfebe3d85a46c56fcf1e9eee7' =>  '3030-final-touch2',
    'f844dbf63cbcce1ef3b4ad4ebf174879f6d9a6ee' =>  '3029-final-touch1',
    '0e9c6d523fffb5ffd550a9b1bf6cb3d7ddaa6ba1' =>  '3028-comment',
    '443c010666eeb0baf6fa152307527cd48d28ab7c' =>  '3027-attachment',
    'f0d1c6b550422ed1c7605c7686d7f0c8dab6fa0e' =>  '3026-tag',
    'aacfbd574ede6fee41fb2a9e5d8f6869cbdada65' =>  '3025-article',
];

$data = array_reverse($data);
//die(var_dump($data));

$green = "\033[32m";
$reset = "\033[0m";

echo "{$green}Start tagging{$reset}" . PHP_EOL;

foreach($data as $commit => $tag) {
    system(sprintf('git checkout %s', $commit));
    system(sprintf('git tag %s', $tag));
}

echo "{$green}Done{$reset}" . PHP_EOL;