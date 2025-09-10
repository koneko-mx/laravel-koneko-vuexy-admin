<?php

declare(strict_types=1);

namespace Koneko\KonekoVuexyAdmin\Application\Traits\User;

use Koneko\KonekoVuexyAdmin\Application\UI\Notifications\CustomResetPasswordNotification;

trait HasPasswordReset
{
    /**
     * Send the password reset notification.
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
