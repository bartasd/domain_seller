<?php

enum State {
    case LIMITED;
    case NORMAL;
}

function invert_state(State $state): State
{
    $filename = __DIR__ . '/../config/app_state.txt';
    $newState = $state == State::LIMITED ? "NORMAL" : "LIMITED";
    file_put_contents($filename, $newState);
}

$state = match (trim(file_get_contents(__DIR__ . '/../config/app_state.txt'))) {
    'LIMITED' => State::LIMITED,
    'NORMAL' => State::NORMAL,
    default => State::NORMAL,
};

return $state;