<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Trabalho;
use App\Models\EducationalContent;
use App\Models\HseTalentProfile;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $jobsFeatured = Trabalho::published()
            ->orderByDesc('is_sponsored')
            ->orderByDesc('is_featured')
            ->latest('published_at')
            ->take(6)
            ->get();

        $educationalFeatured = EducationalContent::published()
            ->orderByDesc('is_featured')
            ->latest('published_at')
            ->take(6)
            ->get();

        $sectorUpdates = \App\Models\Post::whereNotNull('published_at')
            ->whereHas('category', fn($q) => $q->where('slug', 'atualizacoes-do-setor'))
            ->latest('published_at')
            ->take(6)
            ->get();

        $featured = Post::whereNotNull('published_at')
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        $popular = Post::whereNotNull('published_at')
            ->where('is_popular', true)
            ->latest('published_at')
            ->take(6)
            ->get();

        $recent = Post::whereNotNull('published_at')
            ->latest('published_at')
            ->take(6)
            ->get();

        $categories = Category::withCount('posts')->get();

        $videos = Post::whereNotNull('published_at')
            ->whereNotNull('video_url')
            ->latest('published_at')
            ->take(4)
            ->get();
        
        $sections = config('hse_links', []);
        
        // DADOS DA REDE DE PROFISSIONAIS HSE
        // Contagem por especialidade (área)
        $totalSafetyTechnicians = HseTalentProfile::where('area', 'tecnico_trabalho')->count();
        $totalOccupationalDoctors = HseTalentProfile::where('area', 'medico_trabalho')->count();
        $totalPsychologists = HseTalentProfile::where('area', 'psicologos')->count();
        $totalEnvironmentalists = HseTalentProfile::where('area', 'ambientalistas')->count();
        $totalHygienists = HseTalentProfile::where('area', 'higienistas')->count();
        
        // Para ergonomistas, podemos usar um valor fixo ou buscar de outra tabela
        $totalErgonomists = 0; // Se não tiver na tabela, deixamos como 0
        
        // Total geral de profissionais
        $totalProfessionals = HseTalentProfile::count();
        
        // Novos este mês
        $newThisMonth = HseTalentProfile::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Províncias atendidas (distinct)
        $totalProvinces = HseTalentProfile::distinct('province')->count('province');
        
        // Dados para o gráfico
        $professionalsByArea = HseTalentProfile::selectRaw('area, COUNT(*) as total')
            ->groupBy('area')
            ->orderBy('total', 'desc')
            ->get();
        
        // Dados para os modais
        $safetyProfessionals = HseTalentProfile::where('area', 'tecnico_trabalho')
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get(['id', 'full_name', 'phone', 'email', 'current_position', 'years_experience', 'province', 'level']);
        
        $medicalProfessionals = HseTalentProfile::where('area', 'medico_trabalho')
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get(['id', 'full_name', 'phone', 'email', 'current_position', 'years_experience', 'province']);
        
        $psychologyProfessionals = HseTalentProfile::where('area', 'psicologos')
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get(['id', 'full_name', 'phone', 'email', 'current_position', 'years_experience', 'province']);
        
        $environmentalProfessionals = HseTalentProfile::where('area', 'ambientalistas')
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get(['id', 'full_name', 'phone', 'email', 'current_position', 'years_experience', 'province']);
        
        $hygieneProfessionals = HseTalentProfile::where('area', 'higienistas')
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get(['id', 'full_name', 'phone', 'email', 'current_position', 'years_experience', 'province']);

        return view('home', compact(
            'featured', 'categories', 'recent', 'popular', 'videos',
            'jobsFeatured', 'educationalFeatured', 'sectorUpdates', 'sections',
            // Dados da rede de profissionais
            'totalSafetyTechnicians',
            'totalOccupationalDoctors',
            'totalPsychologists',
            'totalEnvironmentalists',
            'totalHygienists',
            'totalErgonomists',
            'totalProfessionals',
            'newThisMonth',
            'totalProvinces',
            'professionalsByArea',
            'safetyProfessionals',
            'medicalProfessionals',
            'psychologyProfessionals',
            'environmentalProfessionals',
            'hygieneProfessionals'
        ));
    }
}