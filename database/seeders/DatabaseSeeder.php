<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Filiere;
use App\Models\AcademicYear;
use App\Models\Memoir;
use App\Models\MemoirKeyword;
use App\Models\MemoirNotification;
use App\Models\Favorite;
use App\Models\ReadingHistory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $filieres = collect(['Finance','Statistique','Management','Économie','Transformation digitale','Comptabilité'])
            ->map(fn($n) => Filiere::create(['name'=>$n,'slug'=>Str::slug($n)]))->keyBy('name');

        $years = collect(['2024–2025','2023–2024','2022–2023','2021–2022'])
            ->values()->map(fn($l,$i)=>AcademicYear::create(['label'=>$l,'is_current'=>$i===0]))->keyBy('label');

        $mkUser = fn($name,$email,$role,$matricule=null,$filiere=null) => User::create([
            'name'=>$name,'email'=>$email,'role'=>$role,'matricule'=>$matricule,
            'filiere_id'=>$filiere ? $filieres[$filiere]->id : null,
            'password'=>Hash::make('password'),
        ]);

        $mkUser('Administrateur ENEAM','admin@eneam.bj','admin');
        $mkUser('Mme Bibliothèque','validateur@eneam.bj','validateur');
        $enc1 = $mkUser('Pr. Anselme HOUÉTO','houeto@eneam.bj','encadreur');
        $enc2 = $mkUser('Dr. Chantal KPADONOU','kpadonou@eneam.bj','encadreur');
        $enc3 = $mkUser('Pr. Serge MENSAH','mensah@eneam.bj','encadreur');
        $enc4 = $mkUser('Dr. Rachida AGOSSOU','agossou@eneam.bj','encadreur');

        $students = [
            $mkUser('Akossiwa HOUNKPÈ','hounkpe@eneam.bj','etudiant','ENEAM-2021-0432','Finance'),
            $mkUser('Romuald ADJOVI','adjovi@eneam.bj','etudiant','ENEAM-2020-1187','Statistique'),
            $mkUser('Mariam BIO TCHANÉ','biotchane@eneam.bj','etudiant','ENEAM-2022-0091','Management'),
            $mkUser('Sèna DOSSOU','dossou@eneam.bj','etudiant','ENEAM-2021-0765','Transformation digitale'),
            $mkUser('Koffi GANDONOU','gandonou@eneam.bj','etudiant','ENEAM-2019-3321','Finance'),
            $mkUser('Fatoumata SALAMI','salami@eneam.bj','etudiant','ENEAM-2022-0512','Statistique'),
        ];

        $data = [
            ["L'impact de la digitalisation sur la performance des PME au Bénin",0,'Finance','2024–2025',$enc1->id,['digitalisation','PME','performance'],1245,87,'publie',"Ce mémoire analyse l'effet de l'adoption des outils numériques sur la performance financière de 212 PME béninoises entre 2019 et 2024. À partir d'un modèle économétrique en données de panel, nous montrons que la digitalisation améliore significativement la marge nette.",false,false],
            ['Analyse statistique de la pauvreté monétaire dans le Zou (2015–2024)',1,'Statistique','2023–2024',$enc2->id,['pauvreté','Zou','EHCVM'],982,112,'publie',"À partir des enquêtes EHCVM, ce travail mesure l'évolution de la pauvreté monétaire dans le département du Zou et identifie ses principaux déterminants.",false,false],
            ["Gouvernance d'entreprise et rentabilité des banques béninoises",4,'Finance','2022–2023',$enc3->id,['gouvernance','banques','rentabilité'],1508,94,'publie',"Étude du lien entre la composition des conseils d'administration et la rentabilité (ROA, ROE) de neuf banques commerciales béninoises.",true,true],
            ["Le mobile money et l'inclusion financière des femmes rurales",2,'Management','2024–2025',$enc4->id,['mobile money','inclusion financière','genre'],764,78,'publie',"Enquête menée auprès de 340 commerçantes de l'Alibori et du Borgou sur l'usage du mobile money comme porte d'entrée vers l'épargne et le crédit formels.",false,false],
            ['Optimisation des chaînes logistiques dans le secteur cotonnier',5,'Statistique','2023–2024',$enc1->id,['coton','logistique','recherche opérationnelle'],611,103,'publie',"Modélisation de la collecte du coton-graine entre les zones de production et les usines d'égrenage, avec un modèle de transport à capacité limitée.",false,false],
            ['Transformation digitale des services publics : cas de la DGI',3,'Transformation digitale','2024–2025',$enc2->id,['digitalisation','e-administration','fiscalité'],1102,81,'valide',"Analyse du programme e-services de la Direction Générale des Impôts et de ses effets sur la conformité fiscale des contribuables.",false,false],
            ['Gestion des ressources humaines et fidélisation des cadres',2,'Management','2022–2023',$enc3->id,['RH','fidélisation','motivation'],538,72,'publie',"Étude qualitative dans quatre entreprises de Cotonou sur les pratiques RH qui retiennent les cadres intermédiaires.",false,false],
            ['Prédiction du décrochage scolaire par apprentissage automatique',1,'Statistique','2024–2025',$enc4->id,['IA','éducation','forêts aléatoires'],1377,96,'publie',"Comparaison de modèles de classification (régression logistique, forêts aléatoires, XGBoost) pour anticiper le décrochage au secondaire.",false,false],
            ["E-gouvernance et confiance des usagers",3,'Transformation digitale','2024–2025',$enc2->id,['digitalisation','gouvernance','e-administration'],0,65,'rejete',"Étude sur la perception des usagers face aux services publics numériques au Bénin.",false,false],
            ["Analyse de la rentabilité des microfinances au Bénin",0,'Finance','2024–2025',$enc1->id,['microfinance','rentabilité','PME'],0,90,'soumis',"Analyse comparative des performances des institutions de microfinance dans le contexte béninois.",false,false],
        ];

        foreach ($data as [$title,$authorIdx,$filiere,$year,$supId,$kws,$reads,$pages,$status,$abstract,$restricted,$noDl]) {
            $m = Memoir::create([
                'author_id'=>$students[$authorIdx]->id,
                'supervisor_id'=>$supId,
                'filiere_id'=>$filieres[$filiere]->id,
                'academic_year_id'=>$years[$year]->id,
                'title'=>$title,'abstract'=>$abstract,'pages'=>$pages,'status'=>$status,
                'reads_count'=>$reads,'submitted_at'=>now()->subDays(rand(10,120)),
                'validated_at'=>in_array($status,['valide','publie'])?now()->subDays(rand(1,30)):null,
                'published_at'=>in_array($status,['valide','publie'])?now()->subDays(rand(1,30)):null,
                'rejection_reason'=>$status==='rejete'?'Page de garde non conforme aux exigences de la bibliothèque.':null,
                'restricted_access'=>$restricted,'download_disabled'=>$noDl || $restricted,
                'embargo_until'=>$restricted?now()->addMonths(4)->toDateString():null,
            ]);
            foreach ($kws as $kw) MemoirKeyword::create(['memoir_id'=>$m->id,'keyword'=>$kw]);
        }

        $published = Memoir::whereIn('status',['valide','publie'])->get();
        foreach ($published->take(3) as $m) Favorite::firstOrCreate(['user_id'=>$students[3]->id,'memoir_id'=>$m->id]);
        foreach ($published->take(4) as $i=>$m) ReadingHistory::create([
            'user_id'=>$students[3]->id,'memoir_id'=>$m->id,'last_page'=>[12,3,45,8][$i],
            'total_pages'=>$m->pages,'last_read_at'=>now()->subHours($i*5+1)
        ]);

        $notifs = [
            [$students[3]->id,$published[0]->id ?? null,'valide','Votre mémoire a été validé','« Transformation digitale des services publics : cas de la DGI » est désormais publié dans le catalogue.','Voir le mémoire','/memoires/'.($published[0]->id ?? 1),null],
            [$students[3]->id,null,'rejete','Dépôt à corriger','Le validateur demande des corrections sur « E-gouvernance et confiance des usagers ». Motif : page de garde non conforme.','Voir le motif','/mes-depots',null],
            [$students[3]->id,$published[0]->id ?? null,'info','Nouveau mémoire dans vos favoris','Pr. Anselme HOUÉTO a publié une version corrigée.','Voir le mémoire','/memoires/'.($published[0]->id ?? 1),null],
            [$students[3]->id,null,'soumis','Dépôt reçu','Votre mémoire a bien été soumis. Un validateur de la bibliothèque le prendra en charge sous 5 jours ouvrés.','Suivre le dépôt','/mes-depots',now()->subDays(2)],
            [$students[3]->id,$published[2]->id ?? null,'info',"Fin de l'embargo",'« Gouvernance d’entreprise et rentabilité des banques béninoises » est de nouveau accessible en lecture.','Lire en ligne','/memoires/'.($published[2]->id ?? 1),now()->subDays(2)],
        ];
        foreach ($notifs as [$uid,$mid,$kind,$title,$msg,$al,$au,$read]) {
            MemoirNotification::create(['user_id'=>$uid,'memoir_id'=>$mid,'kind'=>$kind,'title'=>$title,'message'=>$msg,'action_label'=>$al,'action_url'=>$au,'read_at'=>$read,'created_at'=>now()->subHours(rand(2,120))]);
        }
    }
}
