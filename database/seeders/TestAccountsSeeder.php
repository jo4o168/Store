<?php

namespace Database\Seeders;

use App\Enum\ProfileRole;
use App\Models\ProducerSetting;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestAccountsSeeder extends Seeder
{
    public const PASSWORD = '12345678';

    public function run(): void
    {
        $this->upsertAccount(
            name: 'Cliente Teste',
            email: 'cliente@teste.com',
            role: ProfileRole::CLIENT,
        );

        $profile = $this->upsertAccount(
            name: 'Produtor Teste',
            email: 'produtor@teste.com',
            role: ProfileRole::PRODUCER,
        );

        ProducerSetting::query()->updateOrCreate(
            ['producer_id' => $profile->id],
            [
                'farm_name' => 'Fazenda Teste',
                'address' => 'São Paulo, SP',
            ],
        );
    }

    private function upsertAccount(string $name, string $email, ProfileRole $role): Profile
    {
        $user = User::withTrashed()->where('email', $email)->first();

        $payload = [
            'name' => $name,
            'username' => $email,
            'password' => Hash::make(self::PASSWORD),
            'roles' => $role->value,
            'email_verified_at' => now(),
            'active' => true,
            'deleted_at' => null,
        ];

        if ($user) {
            $user->fill($payload)->save();
        } else {
            $user = User::query()->create([
                ...$payload,
                'email' => $email,
            ]);
        }

        $profile = Profile::withTrashed()->where('user_id', $user->id)->first();

        $profilePayload = [
            'name' => $name,
            'role' => $role->value,
            'email' => $email,
            'deleted_at' => null,
        ];

        if ($profile) {
            $profile->fill($profilePayload)->save();
        } else {
            $profile = Profile::query()->create([
                ...$profilePayload,
                'user_id' => $user->id,
            ]);
        }

        return $profile;
    }
}
