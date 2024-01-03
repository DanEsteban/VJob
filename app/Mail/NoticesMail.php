<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ProcessDataStage;


class NoticesMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProcessDataStage $stage, $customer, $message)
    {
        $this->stage = $stage;
        $this->customer = $customer;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.notice')
        ->with([
            'stage' => $this->stage,
            'customer' => $this->customer,
            'message' => $this->message,
        ]);;
    }
}
