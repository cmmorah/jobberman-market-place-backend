<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;


class SendEmailController extends Controller
{

    public function index()
    {

        Mail::to('cynthiaphillip143@gmail.com')->send((new NotifyMail())->subject('Jacks Snuff'));

        if (Mail::failures()) {
            return 'Sorry! Please try again latter';
        }else{
            return 'Great! Successfully send in your mail';
        }
    }
}
