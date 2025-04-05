<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = Plan::orderBy('id', 'asc')->get();
        return Inertia::render('owner/plans/Index', [
            'plans' => PlanResource::collection($plans),
            'status' => $request->session()->get('status'),
        ]);
    }
    public function show(Plan $plan)
    {

    }
    public function create()
    {
        Plan::create(['name' => 'Базовый', 'slug' => 'basic',
        'description' => 'Подходит для небольших стоматологических кабинетов с минимальным штатом и количеством пациентов.',
            'price' => 35000,
            'features' => [
                '1 клиника',
                'До 2 врачей',
                'До 50 пациентов',
                'Расписание приёмов',
                'Уведомления в WhatsApp (только при записи)',
                'Личный кабинет врача и пациента',
                'История приёмов и платежей',
                'Базовая поддержка',
            ],
        ]);
        
        Plan::create(['name' => 'Профессиональный', 'slug' => 'professional',
        'description' => 'Идеально подходит для развивающихся клиник с несколькими филиалами и расширенным штатом врачей.',
            'price' => 54000,
            'features' => [
                'До 3 клиник',
                'До 10 врачей',
                'До 500 пациентов',
                'Все из Базового',
                'Уведомления в WhatsApp: при записи и за 1 час до приёма',
                'Полный доступ к истории пациентов',
                'Загрузка файлов в карточке пациента',
                'Поддержка через WhatsApp',
            ],
        ]);
        
        Plan::create(['name' => 'Премиум', 'slug' => 'premium',
        'description' => 'Максимальный функционал для сетей клиник и профессионального управления с аналитикой и приоритетной поддержкой.',
            'price' => 99000,
            'features' => [
                'Без ограничений по количеству клиник',
                'Без ограничений по количеству врачей',
                'Без ограничений по количеству пациентов',
                'Все из Профессионального',
                'Аналитика по врачам и финансам',
                'Расширенная история платежей с фильтрами',
                'Приоритетная поддержка',
            ],
        ]);
    }
}
