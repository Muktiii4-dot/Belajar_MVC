<?php

namespace Tests\Feature;

use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_data_is_displayed_on_home_and_student_pages(): void
    {
        $siswa = Siswa::factory()->create(['nama' => 'Siswa Uji']);

        $this->get('/')->assertOk()->assertSee($siswa->nama);
        $this->get('/siswa')->assertOk()->assertSee($siswa->nama);
    }
}
