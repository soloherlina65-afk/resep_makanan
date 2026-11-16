<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserAvatarUrlTest extends TestCase
{
    public function test_external_avatar_url_is_preserved_and_uploaded_path_uses_storage_url(): void
    {
        $user = new User(['avatar' => 'https://images.example.test/avatar.png']);

        $this->assertSame('https://images.example.test/avatar.png', $user->avatarUrl());

        $user->avatar = 'avatars/profile.png';

        $this->assertSame(asset('storage/avatars/profile.png'), $user->avatarUrl());
    }
}
