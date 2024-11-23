<?php

namespace App\Jobs;

use App\Models\Email;
use App\Models\Url;
use App\Services\OlxRequestsFactoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class sendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach(Url::all() as $urlData) {
            $olxResponse = olxRequestsFactoryService::init($urlData->url);
            if (!empty($olxResponse)) {
                Url::where('url', $urlData->url)->update(['actual_price' => $olxResponse]);
            }
        }

        foreach(Email::all() as $email) {
            $urlData = Url::find($email->url_id);
            Mail::raw('Ви просили простежити за сторінкою ' . $urlData->url . '. Повідомляємо, що на неї ціна зараз ' . ($urlData->actual_price ?? 'ще уточнюється') . '.', function ($message) use ($email){
                $message->to($email->email)
                    ->subject('Повідомлення про ціну');
            });
        }
    }
}
