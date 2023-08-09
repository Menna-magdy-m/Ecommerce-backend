<?php


namespace App\Services;

use App\Dtos\Result;
use App\Mail\templates\ResetPasswordMail;
use App\Mail\templates\WelcomeEmail;
use App\Models\User;
use App\Models\UserResetToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PHPUnit\Exception;

class EmailService extends ModelService
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return UserResetToken::query();
    }


    // function that send email to user
    public function sendEmail(string $email, $mailInstance)
    {
        try {
            Mail::to($email)->send($mailInstance);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    // send welcome mail to user
    public function sendWelcomeMail()
    {
        // call send email function
        $this->sendEmail(auth()->user()->user_email, new WelcomeEmail(auth()->user()->user_nicename));

        return $this->ok('message', 'welcome:message:sent');
    }

    // send reset password email
    public function sendResetPasswordMail(array $attributes)
    {
        $email = $attributes['user_email'];
        $user = $this->userService->getUserByEmail($email);
        if ($user instanceof User) {
            // Generate random string code
            $random_string = Str::random(5);
         //   $random_string = "12345";
            $user->resetToken()->delete();
            // save random code to usermeta table
            $user->resetToken()->create([
                'meta_key' => 'reset_password_code',
                'meta_value' => $random_string
            ]);

            // call send email function
            $this->sendEmail($email, new ResetPasswordMail($random_string));

        } else throw new \Exception('user:email:not:found');
        // Store this random string in usermeta table with key "reset_password"
        // Send Random Character code to user
        return $this->ok('message', 'reset:message:sent');
    }

    public function getToken(array $attribute): Result
    {
        $email = $attribute['user_email'];
        $user = $this->userService->getUserByEmail($email);
        if ($user instanceof User) {
            $meta = $user->resetToken()->get()->first();
            if ($meta instanceof UserResetToken) {
                if ($meta->meta_value == $attribute["reset_code"]) {
                    $user->update(["user_pass" => $attribute["new_password"]]);
                    return $this->ok($user, 'records:password:update:done');
                } else
                    throw new \Exception('code:not:found');
            } else
                throw new \Exception('user:email:not:found');
        } else
            throw new \Exception('user:email:not:found');
    }

}
