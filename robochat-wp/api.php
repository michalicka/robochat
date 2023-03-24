<?php

include_once('../../../wp-load.php');

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'rbch_config') {
    $data = [
        'display' => [
            'enabled' => get_option('rbch_display_enabled') == '1',
            'align' => get_option('rbch_display_align', 'right'),
        ],
        'client' => [
            'api' => [
                'url' => get_option('rbch_client_api_url', 'https://api.openai.com/v1/chat/completions'),
                'key' => get_option('rbch_client_api_key'),
                'org' => get_option('rbch_client_api_org'),
            ]
        ],
        'assistant' => [
            'name' => get_option('rbch_assistant_name', 'RoboChat'),
            'image' => get_option('rbch_assistant_image', 'https://cdn.pixabay.com/photo/2023/03/05/21/11/ai-generated-7832244_640.jpg'),
            'limit' => (int)get_option('rbch_assistant_limit'),
            'messages' => [
                'intro' => get_option('rbch_assistant_messages_intro', 'Online Chat'),
                'system' => get_option('rbch_assistant_messages_system'),
                'first' => get_option('rbch_assistant_messages_first'),
                'finished' => get_option('rbch_assistant_messages_finished'),
            ]
        ]
    ];
    echo json_encode($data);
}
