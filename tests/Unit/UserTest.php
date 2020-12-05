<?php


namespace Tests\Unit;

use App\Enum\LicenseType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \TestingSeeder::class]);

        $this->user = $this->signIn();
    }

    /** @test */
    public function it_adds_a_license_for_a_user()
    {
        $this->user->updateLicenses(LicenseType::random());

        $this->assertCount(1, $this->user->licenses);
    }

    /** @test */
    public function it_adds_many_licenses_for_a_user()
    {
        $this->user->updateLicenses([
            LicenseType::random(),
            LicenseType::random(),
            LicenseType::random()
        ]);

        $this->assertCount(3, $this->user->licenses);
    }
}
