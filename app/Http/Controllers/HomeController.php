<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tuteur;
use App\Models\Patient;
use App\Models\DossierPatient;
use App\Models\Consultation;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    

    public function index()
    {
        $dossierCount = DossierPatient::count();
        $patientCount = Patient::count();
        $tuteurCount = Tuteur::count();

        $dossierEnAttend = Consultation::where('etat','enAttente')->count();
        $dossierEnRendezVous = Consultation::where('etat','enRendezVous')->count();
        $dossierEnConsultation = Consultation::where('etat','enConsultation')->count();

        if($dossierEnRendezVous === 0){
            $reussirRendezVous = 0;
        }else{
            $reussirRendezVous = ($dossierEnConsultation/$dossierEnRendezVous)*100;

            if($reussirRendezVous > 100){
                $reussirRendezVous = 100;
            }
        }
       
        return View::make('home', compact('dossierCount','patientCount','tuteurCount','reussirRendezVous'));
    }
}
