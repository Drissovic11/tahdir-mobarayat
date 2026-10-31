<?php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdministrator extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Créer un administrateur sans mot de passe par défaut';
    public function handle(): int
    {
        $name = $this->ask('Nom');
        $email = $this->ask('Adresse e-mail');
        $password = $this->secret('Mot de passe (12 caractères minimum)');
        $confirmation = $this->secret('Confirmer le mot de passe');
        $validator = Validator::make(compact('name', 'email', 'password') + ['password_confirmation'=>$confirmation], [
            'name'=>['required','string','max:255'], 'email'=>['required','email','unique:users,email'],
            'password'=>['required','string','min:12','confirmed'],
        ]);
        if ($validator->fails()) { foreach ($validator->errors()->all() as $error) $this->error($error); return self::FAILURE; }
        $user = new User(compact('name', 'email', 'password'));
        $user->is_admin = true;
        $user->email_verified_at = now();
        $user->save();
        $this->info('Administrateur créé. Connexion sur /admin.');
        return self::SUCCESS;
    }
}
