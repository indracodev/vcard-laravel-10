<?php

namespace App\Helpers;

class VcardHelper
{
    public static function getVcfFileName($fullName): string
    {
        $cleanName = preg_replace('/[\/\\\\?%*:|"<>]/', '', trim((string) $fullName));

        return ($cleanName !== '' ? $cleanName : 'contact').'.vcf';
    }

    public static function buildVcardContent($vcard): string
    {
        $nameParts = array_pad(array_slice(explode(';', (string) $vcard->name), 0, 5), 5, '');
        $formattedN = implode(';', $nameParts);

        $vcardContent = "BEGIN:VCARD\r\n";
        $vcardContent .= "VERSION:3.0\r\n";
        $vcardContent .= "PRODID:-//QR-Code-Generator.com//EN\r\n";
        $vcardContent .= "N:{$formattedN}\r\n";
        $vcardContent .= "FN:{$vcard->full_name}\r\n";
        $vcardContent .= "ORG:PT. Indraco Global Indonesia\r\n";

        if (! empty($vcard->title)) {
            $vcardContent .= "TITLE:{$vcard->title}\r\n";
        }
        if (! empty($vcard->email)) {
            $vcardContent .= "EMAIL;TYPE=PREF,Work:{$vcard->email}\r\n";
        }
        if (! empty($vcard->mobile_phone)) {
            $vcardContent .= "TEL;TYPE=CELL,voice:{$vcard->mobile_phone}\r\n";
        }

        $vcardContent .= "TEL;TYPE=WORK,voice:+62317668777\r\n";
        $vcardContent .= "ADR;TYPE=WORK;type=pref:;;Jl. Semeru No. 133-135 Bambe, Kec. Driyorejo;Gresik;Jawa Timur;61177;Indonesia\r\n";
        $vcardContent .= "URL:www.indracoglobal.com\r\n";
        $vcardContent .= "URL:www.supresso.com\r\n";

        if (! empty($vcard->photo)) {
            $photoPath = public_path('storage/photos/'.$vcard->photo);
            if (is_file($photoPath)) {
                $photoData = base64_encode((string) file_get_contents($photoPath));
                $ext = strtoupper(pathinfo($photoPath, PATHINFO_EXTENSION) ?: 'WEBP');
                $mimeType = $ext === 'JPG' ? 'JPEG' : $ext;
                $vcardContent .= "PHOTO;ENCODING=b;TYPE={$mimeType}:{$photoData}\r\n";
            }
        }

        $vcardContent .= "END:VCARD\r\n";

        return $vcardContent;
    }

    public static function generateVcard($vcard): void
    {
        $dir = public_path('storage/contact');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $safeFileName = self::getVcfFileName($vcard->full_name);
        file_put_contents($dir.'/'.$safeFileName, self::buildVcardContent($vcard));
    }
}
