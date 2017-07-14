<?php namespace Milo\Feedback\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Feedbacks Back-end Controller
 */
class Feedbacks extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Milo.Feedback', 'feedback', 'feedbacks');
    }
}
