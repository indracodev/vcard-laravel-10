<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Enums\VcardStatus;
use App\Helpers\VcardHelper;
use App\Models\Vcard;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_vcard_status_enum_labels_and_badges(): void
    {
        $this->assertSame('Aktif', VcardStatus::Active->label());
        $this->assertSame('text-bg-success', VcardStatus::Active->badgeClass());
        $this->assertSame('Tidak Aktif', VcardStatus::Inactive->label());
        $this->assertSame('text-bg-danger', VcardStatus::Inactive->badgeClass());
    }

    public function test_user_role_enum_labels_and_badges(): void
    {
        $this->assertSame('Administrator', UserRole::Admin->label());
        $this->assertSame('text-bg-primary', UserRole::Admin->badgeClass());
        $this->assertSame('Tim HCS', UserRole::Hcs->label());
        $this->assertSame('text-bg-secondary', UserRole::Hcs->badgeClass());
    }

    public function test_vcard_helper_builds_valid_vcf_content(): void
    {
        $vcard = new Vcard([
            'name' => 'Doe;John',
            'full_name' => 'John Doe',
            'title' => 'Software Engineer',
            'email' => 'john@indracoglobal.com',
            'mobile_phone' => '+6281234567890',
            'link_name' => 'john-doe',
            'status' => VcardStatus::Active,
        ]);

        $content = VcardHelper::buildVcardContent($vcard);

        $this->assertStringContainsString('BEGIN:VCARD', $content);
        $this->assertStringContainsString('FN:John Doe', $content);
        $this->assertStringContainsString('TITLE:Software Engineer', $content);
        $this->assertStringContainsString('EMAIL;TYPE=PREF,Work:john@indracoglobal.com', $content);
        $this->assertStringContainsString('END:VCARD', $content);
    }
}
