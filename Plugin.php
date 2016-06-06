<?php    namespace Lilessam\Mailsender;
use Backend;
use Controller;
use System\Classes\PluginBase;
use Event;
class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'Backend Users Mail Sender',
            'description' => 'Provides a control for sending mails to specific backend members group',
            'author' => 'LilEssam',
            'icon' => 'icon-twitch'
        ];
    }

    public function registerNavigation()
    {
        return [
            'mailsender' => [
                'label'       => 'lilessam.mailsender::lang.plugin.name',
                'url'         => Backend::url('lilessam/mailsender/mailsender'),
                'icon'        => 'icon-twitch',
                'permissions' => [],
                'order'       => 30,

                'sideMenu' => [

                ]
            ]
        ];
    }

}
