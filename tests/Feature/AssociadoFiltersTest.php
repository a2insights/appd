<?php

namespace Tests\Feature;

use App\Models\Associado;
use App\Models\Beneficio;
use App\Models\Carteirinha;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AssociadoFiltersTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar usuário admin para testes
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_filters_by_age_range_0_to_12()
    {
        // Criar associados de diferentes idades
        $crianca = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(5), // 5 anos
        ]);

        $adolescente = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(15), // 15 anos
        ]);

        $adulto = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(30), // 30 anos
        ]);

        // Testar filtro de 0-12 anos
        $currentYear = Carbon::now()->year;
        $results = Associado::whereYear('data_nascimento', '>=', $currentYear - 12)->get();

        $this->assertTrue($results->contains($crianca));
        $this->assertFalse($results->contains($adolescente));
        $this->assertFalse($results->contains($adulto));
    }

    /** @test */
    public function it_filters_by_age_range_13_to_17()
    {
        $crianca = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(10),
        ]);

        $adolescente = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(15),
        ]);

        $adulto = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(25),
        ]);

        $currentYear = Carbon::now()->year;
        $results = Associado::whereYear('data_nascimento', '<=', $currentYear - 13)
            ->whereYear('data_nascimento', '>=', $currentYear - 17)
            ->get();

        $this->assertFalse($results->contains($crianca));
        $this->assertTrue($results->contains($adolescente));
        $this->assertFalse($results->contains($adulto));
    }

    /** @test */
    public function it_filters_by_custom_age_range()
    {
        // Criar associados com idades específicas
        $crianca3anos = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(3),
        ]);

        $crianca5anos = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(5),
        ]);

        $crianca10anos = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(10),
        ]);

        // Filtrar de 3 a 5 anos
        $currentYear = Carbon::now()->year;
        $birthYearMax = $currentYear - 3;
        $birthYearMin = $currentYear - 5;

        $results = Associado::whereYear('data_nascimento', '>=', $birthYearMin)
            ->whereYear('data_nascimento', '<=', $birthYearMax)
            ->get();

        $this->assertTrue($results->contains($crianca3anos));
        $this->assertTrue($results->contains($crianca5anos));
        $this->assertFalse($results->contains($crianca10anos));
    }

    /** @test */
    public function it_filters_by_birth_month()
    {
        $janeiroBirthday = Associado::factory()->create([
            'data_nascimento' => Carbon::create(1990, 1, 15),
        ]);

        $dezembroBirthday = Associado::factory()->create([
            'data_nascimento' => Carbon::create(1985, 12, 25),
        ]);

        $results = Associado::whereRaw('MONTH(data_nascimento) IN (12)')->get();

        $this->assertFalse($results->contains($janeiroBirthday));
        $this->assertTrue($results->contains($dezembroBirthday));
    }

    /** @test */
    public function it_filters_by_has_deficiency()
    {
        $comDeficiencia = Associado::factory()->create([
            'tipo_deficiencia' => \App\TipoDeficiencia::Fisica,
        ]);

        $semDeficiencia = Associado::factory()->create([
            'tipo_deficiencia' => null,
            'causa_deficiencia' => null,
            'cid10' => null,
        ]);

        $results = Associado::whereNotNull('tipo_deficiencia')
            ->orWhereNotNull('causa_deficiencia')
            ->orWhereJsonLength('cid10', '>', 0)
            ->get();

        $this->assertTrue($results->contains($comDeficiencia));
        $this->assertFalse($results->contains($semDeficiencia));
    }

    /** @test */
    public function it_filters_by_has_carteirinha()
    {
        $comCarteirinha = Associado::factory()->create();
        Carteirinha::factory()->create(['associado_id' => $comCarteirinha->id]);

        $semCarteirinha = Associado::factory()->create();

        $results = Associado::has('carteirinhas')->get();

        $this->assertTrue($results->contains($comCarteirinha));
        $this->assertFalse($results->contains($semCarteirinha));
    }

    /** @test */
    public function it_filters_by_has_whatsapp()
    {
        $comWhatsapp = Associado::factory()->create([
            'telefone_whatsapp' => '11999999999',
        ]);

        $semWhatsapp = Associado::factory()->create([
            'telefone_whatsapp' => null,
        ]);

        $results = Associado::whereNotNull('telefone_whatsapp')
            ->where('telefone_whatsapp', '!=', '')
            ->get();

        $this->assertTrue($results->contains($comWhatsapp));
        $this->assertFalse($results->contains($semWhatsapp));
    }

    /** @test */
    public function it_filters_by_beneficios()
    {
        $beneficio = Beneficio::factory()->create(['nome' => 'Bolsa Família']);

        $associadoComBeneficio = Associado::factory()->create();
        $associadoComBeneficio->beneficios()->attach($beneficio);

        $associadoSemBeneficio = Associado::factory()->create();

        $results = Associado::whereHas('beneficios', function ($query) use ($beneficio) {
            $query->where('beneficios.id', $beneficio->id);
        })->get();

        $this->assertTrue($results->contains($associadoComBeneficio));
        $this->assertFalse($results->contains($associadoSemBeneficio));
    }

    /** @test */
    public function it_filters_by_cidade()
    {
        $associadoSP = Associado::factory()->create(['cidade' => 'São Paulo']);
        $associadoRJ = Associado::factory()->create(['cidade' => 'Rio de Janeiro']);

        $results = Associado::whereIn('cidade', ['São Paulo'])->get();

        $this->assertTrue($results->contains($associadoSP));
        $this->assertFalse($results->contains($associadoRJ));
    }

    /** @test */
    public function it_filters_by_multiple_age_ranges()
    {
        // Criar associados de diferentes idades
        $jovem20 = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(20),
        ]);

        $adulto35 = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(35),
        ]);

        $idoso65 = Associado::factory()->create([
            'data_nascimento' => Carbon::now()->subYears(65),
        ]);

        // Filtrar jovens (18-25) OU idosos (60+)
        $currentYear = Carbon::now()->year;

        $results = Associado::where(function ($query) use ($currentYear) {
            // Intervalo 1: 18-25
            $query->orWhere(function ($q) use ($currentYear) {
                $birthYearMax = $currentYear - 18;
                $birthYearMin = $currentYear - 25;
                $q->whereYear('data_nascimento', '>=', $birthYearMin)
                    ->whereYear('data_nascimento', '<=', $birthYearMax);
            });

            // Intervalo 2: 60+
            $query->orWhere(function ($q) use ($currentYear) {
                $birthYearMax = $currentYear - 60;
                $q->whereYear('data_nascimento', '<=', $birthYearMax);
            });
        })->get();

        $this->assertTrue($results->contains($jovem20));
        $this->assertFalse($results->contains($adulto35));
        $this->assertTrue($results->contains($idoso65));
    }

    /** @test */
    public function it_filters_by_carteirinha_renewal_date()
    {
        $associadoComRenovacao = Associado::factory()->create();

        // Criar 2 carteirinhas (renovação)
        Carteirinha::factory()->create([
            'associado_id' => $associadoComRenovacao->id,
            'data_emissao' => Carbon::create(2023, 1, 15),
        ]);
        Carteirinha::factory()->create([
            'associado_id' => $associadoComRenovacao->id,
            'data_emissao' => Carbon::create(2024, 6, 20),
        ]);

        $associadoSemRenovacao = Associado::factory()->create();
        Carteirinha::factory()->create([
            'associado_id' => $associadoSemRenovacao->id,
            'data_emissao' => Carbon::create(2023, 1, 15),
        ]);

        // Filtrar associados com mais de 1 carteirinha no período
        $start = Carbon::create(2024, 1, 1);
        $end = Carbon::create(2024, 12, 31);

        $results = Associado::whereHas('carteirinhas', function ($query) use ($start, $end) {
            $query->whereBetween('data_emissao', [$start, $end]);
        })->has('carteirinhas', '>', 1)->get();

        $this->assertTrue($results->contains($associadoComRenovacao));
        $this->assertFalse($results->contains($associadoSemRenovacao));
    }
}
