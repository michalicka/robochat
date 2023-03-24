<?php
function rbch_options() {
    echo strtr(
        file_get_contents(RBCH_PLUGIN_DIR.'/templates/options.html'),
        [
            '{{ _wpnonce }}' => wp_create_nonce('update-options'),
            '{{ rbch_display_enabled_1 }}' => get_option('rbch_display_enabled') == "1" ? "selected" : "",
            '{{ rbch_display_enabled_0 }}' => get_option('rbch_display_enabled') == "0" ? "selected" : "",
            '{{ rbch_display_align }}' => get_option('rbch_display_align', 'right'),
            '{{ rbch_display_align_left }}' => get_option('rbch_display_align', 'right') === "left" ? "selected" : "",
            '{{ rbch_display_align_right }}' => get_option('rbch_display_align', 'right') === "right" ? "selected" : "",
            '{{ rbch_client_api_url }}' => get_option('rbch_client_api_url', 'https://api.openai.com/v1/chat/completions'),
            '{{ rbch_client_api_key }}' => get_option('rbch_client_api_key'),
            '{{ rbch_client_api_org }}' => get_option('rbch_client_api_org'),
            '{{ rbch_assistant_name }}' => get_option('rbch_assistant_name', 'RoboChat'),
            '{{ rbch_assistant_image }}' => get_option('rbch_assistant_image', 'https://cdn.pixabay.com/photo/2023/03/05/21/11/ai-generated-7832244_640.jpg'),
            '{{ rbch_assistant_limit }}' => get_option('rbch_assistant_limit'),
            '{{ rbch_assistant_messages_intro }}' => get_option('rbch_assistant_messages_intro', 'Online Chat'),
            '{{ rbch_assistant_messages_system }}' => get_option('rbch_assistant_messages_system'),
            '{{ rbch_assistant_messages_first }}' => get_option('rbch_assistant_messages_first'),
            '{{ rbch_assistant_messages_finished }}' => get_option('rbch_assistant_messages_finished'),
            '{{ submit_label }}' => translate('Save Changes'),
        ]
    );
}
