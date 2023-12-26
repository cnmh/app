<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = \Carbon\Carbon::now();

        $password = Hash::make("admin");
        $social = Hash::make("social");
        $medecin = Hash::make("medecin");
        $root = Hash::make("root");

        $rootUser = User::create([
            'name' => 'root',
            'email' => 'root@gmail.com',
            'password' => $root,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $rootUser->assignRole('root');
        $permissionRoot = [
            'index-Permission',
            'create-Permission',
            'show-Permission',
            'store-Permission',
            'edit-Permission',
            'update-Permission',
            'destroy-Permission',
            'index-Role',
            'store-Role',
            'create-Role',
            'show-Role',
            'edit-Role',
            'update-Role',
            'destroy-Role',
            'addPermissionsAuto-Permission',
            'showRolePermission-Permission',
            'assignRolePermission-Permission',
            'getPermissionsAction-Permission',
            'userAssignedPermissions-Permission',
            'index-User',
            'create-User',
            'store-User',
            'show-User',
            'edit-User',
            'update-User',
            'destroy-User',
        ];

        $rootUser->givePermissionTo($permissionRoot);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => $password,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $admin->assignRole('admin');

        $permissionAdmin = [
            'index-EtatCivil',
            'create-EtatCivil',
            'edit-EtatCivil',
            'show-EtatCivil',
            'store-EtatCivil',
            'update-EtatCivil',
            'destroy-EtatCivil',
            'index-Employe',
            'create-Employe',
            'store-Employe',
            'show-Employe',
            'edit-Employe',
            'update-Employe',
            'destroy-Employe',
            'index-CouvertureMedical',
            'create-CouvertureMedical',
            'store-CouvertureMedical',
            'show-CouvertureMedical',
            'edit-CouvertureMedical',
            'update-CouvertureMedical',
            'destroy-CouvertureMedical',
            'index-TypeHandicap',
            'create-TypeHandicap',
            'store-TypeHandicap',
            'show-TypeHandicap',
            'edit-TypeHandicap',
            'update-TypeHandicap',
            'destroy-TypeHandicap',
            'index-Service',
            'create-Service',
            'store-Service',
            'show-Service',
            'edit-Service',
            'update-Service',
            'destroy-Service',
            'index-NiveauScolaire',
            'show-NiveauScolaire',
            'create-NiveauScolaire',
            'store-NiveauScolaire',
            'edit-NiveauScolaire',
            'update-NiveauScolaire',
            'destroy-NiveauScolaire',



        ];
        $admin->givePermissionTo($permissionAdmin);

       // Social service and general medicine fake accounts with permissions for testing.

        $service_social = User::create([
            'name' => 'service social',
            'email' => 'social@gmail.com',
            'password' => $social,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $permissionServiceSocial = [
            "index-DossierPatient",
            "edit-DossierPatient",
            "store-DossierPatient",
            "update-DossierPatient",
            "index-Consultation",
            "index-RendezVous",
            "show-Consultation",
            "list_dossier-RendezVous",
            "create-RendezVous",
            "store-RendezVous",
            "show-RendezVous",
            "edit-RendezVous",
            "destroy-RendezVous",
            "create-DossierPatient",
            "parent-DossierPatient",
            "create-Tuteur",
            "patient-DossierPatient",
            "store-Tuteur",
            "show-Tuteur",
            "edit-Tuteur",
            "destroy-Tuteur",
            "update-Tuteur",
            "index-Tuteur",
            "create-Patient",
            "store-Patient",
            "entretien-DossierPatient",
            "show-DossierPatient",

        ];
        $service_social->givePermissionTo($permissionServiceSocial);
        $medecin_generale = User::create([
            'name' => 'Medecin générale',
            'email' => 'medecin@gmail.com',
            'password' => $medecin,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $permissionMedecinGenerale = [
            "index-Consultation",
            "show-Consultation",
            "edit-Consultation",
            "patient-Consultation",
            "create-Consultation",
            "show-DossierPatient",
            "Ajouter_RendezVous-Consultation",
            "index-DossierPatient",
            "store-Consultation",
            "destroy-RendezVous",
        ];
        $medecin_generale->givePermissionTo($permissionMedecinGenerale);

    }
}
