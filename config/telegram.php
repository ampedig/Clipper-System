<?php

return [
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'chat_id' => env('TELEGRAM_CHAT_ID'),

    'topics' => [
        'clip_submit' => env('TELEGRAM_TOPIC_CLIP_SUBMIT', 5),
        'commission' => env('TELEGRAM_TOPIC_COMMISSION', 4),
        'activity' => env('TELEGRAM_TOPIC_ACTIVITY', 15),
        'withdraw' => env('TELEGRAM_TOPIC_WITHDRAW', 3),
        'user' => env('TELEGRAM_TOPIC_USER', 2),
        'system' => env('TELEGRAM_TOPIC_SYSTEM', 173),
    ],
];
