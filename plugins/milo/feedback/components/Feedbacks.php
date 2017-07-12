<?php namespace Milo\Feedback\Components;

use Cms\Classes\ComponentBase;
use Milo\Feedback\Models\Feedback;
use Validator;
use Input;
use Flash;
use Redirect;

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

	public function onSend()
	{
		$validator = Validator::make(
			[
				'name' => Input::get('name'),
				'email'     => Input::get('email'),
				'comment'   => Input::get('comment')
			],
			[
				'name' => 'required',
				'email'     => 'required|email',
				'comment'   => 'required'
			],
			[
				'name.required' => 'Dein Name wird benötigt.',
				'email.required'     => 'Deine korrkete E-Mail Adresse wird benötigt',
				'comment.required'   => 'Kommentar wird benötigt.'
			]
		);


		if ( $validator->fails() )
		{
			throw new \ValidationException($validator);
		} else
		{
			$vars = [
				'name'  => Input::get('name'),
				'email'      => Input::get('email'),
				'comment'    => Input::get('comment')
			];

			//var_dump($vars);

			Feedback::create([
				'name'  => $vars['name'],
				'email'      => $vars['email'],
				'comment'    => $vars['comment']
			]);


			/* @todo: Configure Mail Send after submitting new Comment
			already created views/mail/message.htm*/
			/*			Mail::send('milo.receipt::mail.message', $vars, function($message) {

							$message->from('office@mollydarcys.at', 'Molly Darcys Irish Pub');
							$message->to('office@mollydarcys.at', 'Molly Darcys Irish Pub');
							$message->cc(Input::get('email'), Input::get('name'));
							$message->subject('autoreply: your Reservation request');

						});*/

			Flash::success('Kommentar wurde übermittelt!');

			return Redirect::back();
		}
	}

}
