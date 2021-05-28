<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

use App\Models\Access\User\User;

use App\Models\Map\Evaluation;

class NewEvaluateWebpush extends Notification
{
    use Queueable;

    private $evaluation; //valutazione
    private $evaluable; //relation o way...
    public $title;
    public $body;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($evaluation, $evaluable)
    {
        //
        $this->evaluation = $evaluation;
        $this->evaluable = $evaluable;



    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
            'evaluation' => $this->evaluation->id,
            'evaluable' => $this->evaluable->id
        ];
    }





    public function toWebPush($notifiable, $notification)
    {

        $eval = Evaluation::find($this->evaluation->id);


        $time = \Carbon\Carbon::now();
        //if ($this->title = ""){
            $this->title = 'Nuova segnalazione su '.$this->evaluable->getName().': ';
        //}
        //if ($this->body = ""){
            //$this->body = 'Clicca qui per vedere la segnalazione';
            $this->body = $eval->rating_desc;
        //}

        //if ($baseClass = class_basename($evaluable) == "Relation")
        $baseClass = class_basename($this->evaluable);
        \Log::info('classe: ' . $baseClass);
        if ($baseClass == "Relation")
        {
            $url = route('viewRelationMap',['relid' => $this->evaluable->id]);
        }
        elseif ($baseClass == "StravaSegment") {
            $url = route('viewSegmentMap',['segment_id' => $this->evaluable->id]);
        }

        \Log::info('url webpush: ' . $url);

      return (new WebPushMessage)
            ->title($this->title)
            ->icon(url('/img/logo/logo468_t.png'))
            ->body($this->body)
            ->action('Visualizza il Sentiero',
            [
                'action' => 'visualizza_sentiero',
                'title' => 'Visualizza Sentiero',
                'icon'  => url('/img/logo/logo468_t.png')
            ])
            ->data(
                [
                'url' => $url
                ])
            // ->badge()
            // ->dir()
            // ->image()
            // ->lang()
            // ->renotify()
            // ->requireInteraction()
             ->tag('Strails');
            // ->vibrate()

    }
}
