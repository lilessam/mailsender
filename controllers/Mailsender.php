<?php namespace Lilessam\Mailsender\Controllers;
use DB;
use Flash;
use Redirect;
use BackendMenu;
use Backend\Classes\Controller;
use Backend\Models\User;
use ApplicationException;
use Mail;
use Lang;

 /*****************************************************************************
 ******************************************************************************
 ************************************ Mail Sender *****************************
 *                                                                            *
 *                              Developed By Lil'Essam                        *
 *                                                                            *
 *****************************************************************************/

class Mailsender extends Controller
{
    /**
     * Implementing Behaviors
     * */
    public $implement = [
        'Backend.Behaviors.ListController',
    ];

    /**
     * Setting Configurations
     * */
    public $listConfig = 'config_list.yaml';

    /**
     * __Cunstructing
     * */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Lilessam.Mailsender', 'mailsender', 'mailsender');
    }

    /**
     * Passing to form
     * */
    public function index()
    {
        $this->vars['groups'] = DB::table('backend_user_groups')->get();
        $this->asExtension('ListController')->index();

    }

    /**
     * Sending Mail
     * */
    public function onSendMail()
    {
        /**
         * Getting form results
         * */
        $group_id = post('group');
        $subject = post('subject');
        $msg = post('message');
        $test_email = post('testEmail');
        
        /**
         * Checking if there's no data
         * */
        if($subject == "" || $msg == ""):
            return Flash::warning(Lang::get('lilessam.mailsender::lang.error_nodata'));
        endif;
        
        /**
         * Striping tags for the plain version of mail
         * */
        $msgPlain = strip_tags(post('message'));
        
        /**
         * Setting vars array for mail template
         * */
        $vars = [
            'subject' => $subject,
            'msg' => $msg,
            'msgPlain' => $msgPlain,
            ];
            
        /**
         * Check if the administrator want to send only a test message
         * */
        if($test_email != "")
        {
            //email and subject array
            $array = [
            'email' => $test_email,
            'subject'=>$subject
            ];
            //Sending mail
            Mail::send([
                        'text' => $msgPlain,
                        'html' => $msg,
                        'raw' => true
                    ], $vars, function($message) use ($array){
            		$message->subject($array['subject']);
		    	$message->to($array['email'], "Test Reciever");
	    	});
	    	
	    	/**
	    	 * Success message
	    	 * */
	    	return Flash::success(Lang::get('lilessam.mailsender::lang.test.sent'));
        }
            
        
        
        /**
         * Getting users count in this group
         * */
        $users_count = DB::table('backend_users_groups')->where('user_group_id', $group_id)->count();

        /**
         * Checking if there's users in the group
         * */
            if($users_count != 0):
                //Fetching users
                $users_ids = DB::table('backend_users_groups')->where('user_group_id', $group_id)->get();

                /**
                 * Looping to send mail to every user
                 * */
                foreach($users_ids as $user_id){
                    //The user
                    $user = User::where('id', $user_id->user_id)->first();
                    //User and subject array
                    $array = [
                    'user' => $user,
                    'subject'=>$subject
                    ];
                    //Sending mail
                    Mail::send([
                                'text' => $msgPlain,
                                'html' => $msg,
                                'raw' => true
                            ], $vars, function($message) use ($array){
                    		$message->subject($array['subject']);
        		    	$message->to($array['user']->email, $array['user']->login);
        	    	});
                }
            /**
             * Success Message
             * */
            Flash::success(Lang::get('lilessam.mailsender::lang.sent').$users_count.Lang::get('lilessam.mailsender::lang.users'));
        else:
            /**
             * Warning message that there's no users in this group
             * */
            Flash::warning(Lang::get('lilessam.mailsender::lang.nousers'));
        endif;

    }
    
    /**
     * This function checks if there's a value in test email field.
     * if there's any value the send button to all group will be hidden
     * */
    public function onCheckTestEmail()
    {
        if(post('testEmail') == ""){
		return  ['correct'=> 0];
    	}else{
    		return  ['correct'=> 1];
    	}
    }
}
