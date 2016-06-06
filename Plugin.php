<?php    namespace Lilessam\Mailsender;
use Backend;
use Controller;
use System\Classes\PluginBase;
use Event;
class Plugin extends PluginBase
{
    /**
     * This declares basic information about plugin and its author
     * */
    public function pluginDetails()
    {
        return [
            'name'          => 'Backend Users Mail Sender',
            'description'   => 'Provides a control for sending mails to specific backend members group',
            'author'        => 'LilEssam',
            'icon'          => 'icon-twitch'
        ];
    }

    /**
     * This method registers this plugin permission
     * The admin can set this permission to a specific user or a whole group that will access the plugin
     * */
    public function registerPermissions()
    {
        return [
            'lilessam.mailsender.access' => [
                'label'     => 'Access to backend mail sender plugin',
                'tab'       => 'Mail Sender'
            ],
        ];
    }
    
    /**
     * This method declares the settings of backend url and label in OctoberCMS control panel menu
     * */
    public function registerNavigation()
    {
        return [
            'mailsender' => [
                'label'       => 'lilessam.mailsender::lang.plugin.name',
                'url'         => Backend::url('lilessam/mailsender/mailsender'),
                'icon'        => 'icon-twitch',
                'permissions' => ['lilessam.mailsender.access'],
                'order'       => 30,
            ]
        ];
    }

}
