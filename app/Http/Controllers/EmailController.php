<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Mail\templates\ResetPasswordMail;
use App\Mail\templates\WelcomeEmail;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\UserResetToken;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use function GuzzleHttp\Promise\all;

class EmailController extends Controller
{

    private $service;

    public function __construct(EmailService $service)
    {
        $this->service = $service;
    }

    public function sendEmail(Request $request)
    {
        return $this->ok($this->service->sendWelcomeMail());
    }

    public function resetPassword(Request $request)
    {
        return $this->ok($this->service->sendResetPasswordMail($request->all()));
    }

    public function confirmResetPassword(ResetPasswordRequest $request)
    {
        return $this->ok($this->service->getToken($request->all()));
    }
}


/**
 *  // add email input
 * $validate = $request->validate([
 * 'reset_code' => 'required|max:5',
 * 'new_password' => 'required',
 * 'confirm_new_password' => 'required'
 * ]);
 *
 * // get authenticated user model instance
 *
 *
 * return response()->json([
 * 'data' => $validate,
 * 'user' => $user,
 * //            'code' => $metaKey,
 * 'statusCode' => 0
 * ]);
 * /*        return response()->json([
 * 'message' => 'email:sent:done',
 * 'statusCode' => 0
 * ]);
 */
