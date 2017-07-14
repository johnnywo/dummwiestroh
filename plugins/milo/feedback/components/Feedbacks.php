<?php namespace Milo\Feedback\Components;

use Cms\Classes\ComponentBase;
use Milo\Feedback\Models\Feedback;
use Validator;
use Input;
use Flash;
use Redirect;
use Mail;

class Feedbacks extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Feedback Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

	public function index()
	{
		$entries = Feedback::orderBy('created_at', 'DESC')->get();

		return $entries;
    }

	public function onSend()
	{
		$url = Input::get('url');
		$validator = Validator::make(
			[
				'name'      => Input::get('name'),
				'email'     => Input::get('email'),
				'comment'   => Input::get('comment')
			],
			[
				'name'      => 'required',
				'email'     => 'required|email',
				'comment'   => 'required'
			],
			[
				'name.required'      => 'Dein Name wird benötigt.',
				'email.required'     => 'Deine korrkete E-Mail Adresse wird benötigt',
				'comment.required'   => 'Kommentar wird benötigt.'
			]
		);


		if ( $validator->fails() )
		{
			return Redirect::to('/feedback')->withErrors($validator);
		} else {

			if (isset($url) && $url == '')
			{
				$vars['name']       = Input::get('name');
				$vars['email']      = Input::get('email');
				$vars['comment']    = Input::get('comment');

				Feedback::create([
					'name'       => $vars['name'],
					'email'      => $vars['email'],
					'comment'    => $vars['comment']
				]);

				/* @todo: Configure Mail Send after submitting new Comment
				already created views/mail/message.htm*/
				Mail::send('milo.feedback::mail.message', $vars, function($message) {

					$message->from(Input::get('email'), Input::get('name'));
					$message->to('office@zeero.at', 'Dummwiestroh Manager');
					$message->cc('emil@zeero.at', 'Emil');
					$message->subject('neues Feedback dummwiestroh.at');

				});

				Flash::success('Kommentar wurde übermittelt!');

				return Redirect::back();

			}



			Flash::error('Humans only.');
			return Redirect::to('/');
		}
	}

}
