<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        // NOTE: Fortify is NOT registered in this app (it uses a custom phone-based
        // AuthController). This class is dead code kept for reference only.
        // The 'role' field is explicitly set so that IF this were ever enabled,
        // it would not create an account without a role.
        return User::create([
            'name'     => $input['name'],
            'email'    => $input['email'] ?? null,
            'password' => $input['password'],
            'role'     => 'customer',
        ]);
    }
}
