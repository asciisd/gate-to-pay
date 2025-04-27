<?php

namespace ASCIISD\GateToPay\Helpers;

class NationalityHelper
{
    /**
     * Get all nationalities with their codes.
     *
     * @return array
     */
    public static function all(): array
    {
        return self::getNationalities();
    }

    /**
     * Get nationality code by country name.
     *
     * @param string $countryName Country name in English or Arabic
     * @return string|null
     */
    public static function getCodeByCountry(string $countryName): ?string
    {
        $nationalities = self::getNationalities();

        foreach ($nationalities as $nationality) {
            if (
                mb_strtolower($nationality['en']) === mb_strtolower($countryName) ||
                mb_strtolower($nationality['ar']) === mb_strtolower($countryName)
            ) {
                return $nationality['code'];
            }
        }

        return null;
    }

    /**
     * Get nationality by code.
     *
     * @param string $code
     * @return array|null
     */
    public static function getByCode(string $code): ?array
    {
        $nationalities = self::getNationalities();

        foreach ($nationalities as $nationality) {
            if ($nationality['code'] === $code) {
                return $nationality;
            }
        }

        return null;
    }

    /**
     * Get all nationalities data.
     *
     * @return array
     */
    private static function getNationalities(): array
    {
        return [
            ['ar' => 'ألبانيا', 'en' => 'Albania', 'code' => '8'],
            ['ar' => 'الجزائر', 'en' => 'Algeria', 'code' => '12'],
            ['ar' => 'ساموا الغربية', 'en' => 'Western Samoa', 'code' => '882'],
            ['ar' => 'أنغولا', 'en' => 'Angola', 'code' => '24'],
            ['ar' => 'أنغويلا', 'en' => 'Anguilla', 'code' => '660'],
            ['ar' => 'أنتيغوا وبربودا', 'en' => 'Antigua and Barbuda', 'code' => '28'],
            ['ar' => 'الأرجنتين', 'en' => 'Argentina', 'code' => '32'],
            ['ar' => 'أرمينيا', 'en' => 'Armenia', 'code' => '51'],
            ['ar' => 'أروبا', 'en' => 'Aruba', 'code' => '533'],
            ['ar' => 'أستراليا', 'en' => 'Australia', 'code' => '36'],
            ['ar' => 'النمسا', 'en' => 'Austria', 'code' => '40'],
            ['ar' => 'جزر الباهاما', 'en' => 'Bahamas', 'code' => '44'],
            ['ar' => 'البحرين', 'en' => 'Bahrain', 'code' => '48'],
            ['ar' => 'بنغلاديش', 'en' => 'Bangladesh', 'code' => '50'],
            ['ar' => 'بربادوس', 'en' => 'Barbados', 'code' => '52'],
            ['ar' => 'بيلاروسيا', 'en' => 'Belarus', 'code' => '112'],
            ['ar' => 'بلجيكا', 'en' => 'Belgium', 'code' => '56'],
            ['ar' => 'بليز', 'en' => 'Belize', 'code' => '84'],
            ['ar' => 'بنين', 'en' => 'Benin', 'code' => '204'],
            ['ar' => 'برمودا', 'en' => 'Bermuda', 'code' => '60'],
            ['ar' => 'بوتان', 'en' => 'Bhutan', 'code' => '64'],
            ['ar' => 'بوليفيا', 'en' => 'Bolivia', 'code' => '68'],
            ['ar' => 'بونير', 'en' => 'Bonaire', 'code' => '535'],
            ['ar' => 'البوسنة والهرسك', 'en' => 'Bosnia and Herzegovina', 'code' => '70'],
            ['ar' => 'بوتسوانا', 'en' => 'Botswana', 'code' => '72'],
            ['ar' => 'البرازيل', 'en' => 'Brazil', 'code' => '76'],
            ['ar' => 'بروناي دار السلام', 'en' => 'Brunei Darussalam', 'code' => '96'],
            ['ar' => 'بلغاريا', 'en' => 'Bulgaria', 'code' => '100'],
            ['ar' => 'بوركينا فاسو', 'en' => 'Burkina Faso', 'code' => '854'],
            ['ar' => 'بوروندي', 'en' => 'Burundi', 'code' => '108'],
            ['ar' => 'كمبوديا', 'en' => 'Cambodia', 'code' => '116'],
            ['ar' => 'الكاميرون', 'en' => 'Cameroon', 'code' => '120'],
            ['ar' => 'كندا', 'en' => 'Canada', 'code' => '124'],
            ['ar' => 'الرأس الأخضر', 'en' => 'Cape Verde', 'code' => '132'],
            ['ar' => 'جزر كايمان', 'en' => 'Cayman Islands', 'code' => '136'],
            ['ar' => 'جمهورية أفريقيا الوسطى', 'en' => 'Central African Republic', 'code' => '140'],
            ['ar' => 'تشاد', 'en' => 'Chad', 'code' => '148'],
            ['ar' => 'تشيلي', 'en' => 'Chile', 'code' => '152'],
            ['ar' => 'الصين', 'en' => 'China', 'code' => '156'],
            ['ar' => 'كولومبيا', 'en' => 'Colombia', 'code' => '170'],
            ['ar' => 'جزر القمر', 'en' => 'Comoros', 'code' => '174'],
            ['ar' => 'جمهورية الكونغو', 'en' => 'Congo', 'code' => '178'],
            ['ar' => 'الكونغو', 'en' => 'Congo', 'code' => '180'],
            ['ar' => 'كوستاريكا', 'en' => 'Costa Rica', 'code' => '188'],
            ['ar' => 'كوت ديفوار (ساحل العاج)', 'en' => 'Cote d\'Ivoire', 'code' => '531'],
            ['ar' => 'كرواتيا', 'en' => 'Croatia', 'code' => '191'],
            ['ar' => 'كوراساو', 'en' => 'Curacao', 'code' => '531'],
            ['ar' => 'قبرص', 'en' => 'Cyprus', 'code' => '196'],
            ['ar' => 'جمهورية التشيك', 'en' => 'Czech Republic', 'code' => '203'],
            ['ar' => 'الدنمارك', 'en' => 'Denmark', 'code' => '208'],
            ['ar' => 'جيبوتي', 'en' => 'Djibouti', 'code' => '262'],
            ['ar' => 'دومينيكا', 'en' => 'Dominica', 'code' => '212'],
            ['ar' => 'جمهورية الدومينيكان', 'en' => 'Dominican Republic', 'code' => '214'],
            ['ar' => 'الإكوادور', 'en' => 'Ecuador', 'code' => '218'],
            ['ar' => 'مصر', 'en' => 'Egypt', 'code' => '818'],
            ['ar' => 'السلفادور', 'en' => 'El Salvador', 'code' => '222'],
            ['ar' => 'غينيا الاستوائية', 'en' => 'EQUATORIAL GUINEA', 'code' => '226'],
            ['ar' => 'إريتريا', 'en' => 'Eritrea', 'code' => '232'],
            ['ar' => 'إستونيا', 'en' => 'Estonia', 'code' => '233'],
            ['ar' => 'إثيوبيا', 'en' => 'Ethiopia', 'code' => '231'],
            ['ar' => 'فيجي', 'en' => 'Fiji', 'code' => '242'],
            ['ar' => 'فنلندا', 'en' => 'Finland', 'code' => '246'],
            ['ar' => 'فرنسا', 'en' => 'France', 'code' => '250'],
            ['ar' => 'غويانا الفرنسية', 'en' => 'FRENCH GUIANA', 'code' => '254'],
            ['ar' => 'بولينيزيا الفرنسية', 'en' => 'French Polynesia', 'code' => '258'],
            ['ar' => 'الغابون', 'en' => 'Gabon', 'code' => '266'],
            ['ar' => 'غامبيا', 'en' => 'Gambia', 'code' => '270'],
            ['ar' => 'جورجيا', 'en' => 'Georgia', 'code' => '268'],
            ['ar' => 'ألمانيا', 'en' => 'Germany', 'code' => '276'],
            ['ar' => 'غانا', 'en' => 'Ghana', 'code' => '288'],
            ['ar' => 'جبل طارق', 'en' => 'Gibraltar', 'code' => '292'],
            ['ar' => 'اليونان', 'en' => 'Greece', 'code' => '300'],
            ['ar' => 'غرينادا', 'en' => 'Grenada', 'code' => '308'],
            ['ar' => 'غوام', 'en' => 'Guam', 'code' => '316'],
            ['ar' => 'غواتيمالا', 'en' => 'Guatemala', 'code' => '320'],
            ['ar' => 'غينيا', 'en' => 'Guinea', 'code' => '324'],
            ['ar' => 'غيانا', 'en' => 'Guyana', 'code' => '328'],
            ['ar' => 'هايتي', 'en' => 'Haiti', 'code' => '332'],
            ['ar' => 'هندوراس', 'en' => 'Honduras', 'code' => '340'],
            ['ar' => 'هونغ كونغ', 'en' => 'Hong Kong', 'code' => '344'],
            ['ar' => 'المجر', 'en' => 'Hungary', 'code' => '348'],
            ['ar' => 'أيسلندا', 'en' => 'Iceland', 'code' => '352'],
            ['ar' => 'الهند', 'en' => 'India', 'code' => '356'],
            ['ar' => 'إندونيسيا', 'en' => 'Indonesia', 'code' => '360'],
            ['ar' => 'العراق', 'en' => 'Iraq', 'code' => '368'],
            ['ar' => 'أيرلندا', 'en' => 'Ireland', 'code' => '372'],
            ['ar' => 'إسرائيل', 'en' => 'Israel', 'code' => '376'],
            ['ar' => 'إيطاليا', 'en' => 'Italy', 'code' => '380'],
            ['ar' => 'جامايكا', 'en' => 'Jamaica', 'code' => '388'],
            ['ar' => 'اليابان', 'en' => 'Japan', 'code' => '392'],
            ['ar' => 'الأردن', 'en' => 'Jordan', 'code' => '400'],
            ['ar' => 'كازاخستان', 'en' => 'Kazakhstan', 'code' => '398'],
            ['ar' => 'كينيا', 'en' => 'Kenya', 'code' => '404'],
            ['ar' => 'كوريا الجنوبية', 'en' => 'South Korea', 'code' => '410'],
            ['ar' => 'جمهورية كوسوفو', 'en' => 'Republic of Kosovo', 'code' => '383'],
            ['ar' => 'الكويت', 'en' => 'Kuwait', 'code' => '414'],
            ['ar' => 'قيرغيزستان', 'en' => 'Kyrgyzstan', 'code' => '417'],
            ['ar' => 'لاوس', 'en' => 'Laos', 'code' => '426'],
            ['ar' => 'لاتفيا', 'en' => 'Latvia', 'code' => '428'],
            ['ar' => 'لبنان', 'en' => 'Lebanon', 'code' => '422'],
            ['ar' => 'ليسوتو', 'en' => 'Lesotho', 'code' => '426'],
            ['ar' => 'ليبيريا', 'en' => 'Liberia', 'code' => '430'],
            ['ar' => 'ليبيا', 'en' => 'Libya', 'code' => '434'],
            ['ar' => 'ليتوانيا', 'en' => 'Lithuania', 'code' => '440'],
            ['ar' => 'لوكسمبورغ', 'en' => 'Luxembourg', 'code' => '442'],
            ['ar' => 'ماكاو', 'en' => 'Macao', 'code' => '446'],
            ['ar' => 'مقدونيا', 'en' => 'Macedonia', 'code' => '807'],
            ['ar' => 'مدغشقر', 'en' => 'Madagascar', 'code' => '450'],
            ['ar' => 'مالاوي', 'en' => 'Malawi', 'code' => '454'],
            ['ar' => 'ماليزيا', 'en' => 'Malaysia', 'code' => '458'],
            ['ar' => 'جزر المالديف', 'en' => 'Maldives', 'code' => '462'],
            ['ar' => 'مالي', 'en' => 'Mali', 'code' => '466'],
            ['ar' => 'مالطا', 'en' => 'Malta', 'code' => '470'],
            ['ar' => 'جزر مارشال', 'en' => 'Marshall Islands', 'code' => '584'],
            ['ar' => 'موريتانيا', 'en' => 'Mauritania', 'code' => '478'],
            ['ar' => 'موريشيوس', 'en' => 'Mauritius', 'code' => '480'],
            ['ar' => 'مايوت', 'en' => 'Mayotte', 'code' => '175'],
            ['ar' => 'المكسيك', 'en' => 'Mexico', 'code' => '484'],
            ['ar' => 'ولايات ميكرونيزيا المتحدة', 'en' => 'Federated States of Micronesia', 'code' => '583'],
            ['ar' => 'جمهورية مولدوفا', 'en' => 'Republic of Moldova', 'code' => '489'],
            ['ar' => 'منغوليا', 'en' => 'Mongolia', 'code' => '496'],
            ['ar' => 'مونتسيرات', 'en' => 'Montserrat', 'code' => '500'],
            ['ar' => 'المغرب', 'en' => 'Morocco', 'code' => '504'],
            ['ar' => 'موزمبيق', 'en' => 'Mozambique', 'code' => '508'],
            ['ar' => 'ناميبيا', 'en' => 'Namibia', 'code' => '516'],
            ['ar' => 'نيبال', 'en' => 'Nepal', 'code' => '524'],
            ['ar' => 'هولندا', 'en' => 'Netherlands', 'code' => '528'],
            ['ar' => 'كاليدونيا الجديدة', 'en' => 'New Caledonia', 'code' => '540'],
            ['ar' => 'نيوزيلندا', 'en' => 'New Zealand', 'code' => '554'],
            ['ar' => 'نيكاراغوا', 'en' => 'Nicaragua', 'code' => '558'],
            ['ar' => 'النيجر', 'en' => 'Niger Republic', 'code' => '562'],
            ['ar' => 'نيجيريا', 'en' => 'Nigeria', 'code' => '566'],
            ['ar' => 'النرويج', 'en' => 'Norway', 'code' => '578'],
            ['ar' => 'عمان', 'en' => 'Oman', 'code' => '512'],
            ['ar' => 'باكستان', 'en' => 'Pakistan', 'code' => '586'],
            ['ar' => 'فلسطين', 'en' => 'Palestine', 'code' => '275'],
            ['ar' => 'بنما', 'en' => 'Panama', 'code' => '591'],
            ['ar' => 'بابوا غينيا الجديدة', 'en' => 'Papua New Guinea', 'code' => '598'],
            ['ar' => 'باراغواي', 'en' => 'Paraguay', 'code' => '600'],
            ['ar' => 'بيرو', 'en' => 'Peru', 'code' => '604'],
            ['ar' => 'الفلبين', 'en' => 'Philippines', 'code' => '608'],
            ['ar' => 'بولندا', 'en' => 'Poland', 'code' => '616'],
            ['ar' => 'البرتغال', 'en' => 'Portugal', 'code' => '620'],
            ['ar' => 'بورتوريكو', 'en' => 'Puerto Rico', 'code' => '630'],
            ['ar' => 'ريونيون', 'en' => 'Reunion', 'code' => '638'],
            ['ar' => 'رومانيا', 'en' => 'Romania', 'code' => '642'],
            ['ar' => 'روسيا', 'en' => 'Russian Federation', 'code' => '643'],
            ['ar' => 'رواندا', 'en' => 'Rwanda', 'code' => '646'],
            ['ar' => 'سانت كيتس ونيفيس', 'en' => 'Saint Kitts and Nevis', 'code' => '659'],
            ['ar' => 'سانت لوسيا', 'en' => 'Saint Lucia', 'code' => '662'],
            ['ar' => 'سانت فنسنت وجزر غرينادين', 'en' => 'Saint Vincent and the Grenadines', 'code' => '670'],
            ['ar' => 'ساو تومي وبرينسيب', 'en' => 'Sao Tome & Principe', 'code' => '678'],
            ['ar' => 'المملكة العربية السعودية', 'en' => 'Saudi Arabia', 'code' => '682'],
            ['ar' => 'السنغال', 'en' => 'Senegal', 'code' => '686'],
            ['ar' => 'صربيا', 'en' => 'Serbia', 'code' => '688'],
            ['ar' => 'سيشيل', 'en' => 'Seychelles', 'code' => '690'],
            ['ar' => 'سيراليون', 'en' => 'Sierra Leone', 'code' => '694'],
            ['ar' => 'سنغافورة', 'en' => 'Singapore', 'code' => '702'],
            ['ar' => 'سلوفاكيا', 'en' => 'Slovakia', 'code' => '703'],
            ['ar' => 'سلوفينيا', 'en' => 'Slovenia', 'code' => '705'],
            ['ar' => 'جنوب أفريقيا', 'en' => 'South Africa', 'code' => '710'],
            ['ar' => 'السودان', 'en' => 'Sudan', 'code' => '736'],
            ['ar' => 'إسبانيا', 'en' => 'Spain', 'code' => '724'],
            ['ar' => 'سريلانكا', 'en' => 'Sri Lanka', 'code' => '144'],
            ['ar' => 'سورينام', 'en' => 'Suriname', 'code' => '740'],
            ['ar' => 'السويد', 'en' => 'Sweden', 'code' => '752'],
            ['ar' => 'سويسرا', 'en' => 'Switzerland', 'code' => '756'],
            ['ar' => 'تايوان', 'en' => 'Taiwan', 'code' => '158'],
            ['ar' => 'طاجيكستان', 'en' => 'Tajikistan', 'code' => '762'],
            ['ar' => 'تنزانيا', 'en' => 'Tanzania', 'code' => '834'],
            ['ar' => 'تايلاند', 'en' => 'Thailand', 'code' => '764'],
            ['ar' => 'تيمور الشرقية', 'en' => 'East Timor', 'code' => '626'],
            ['ar' => 'توغو', 'en' => 'Togo', 'code' => '768'],
            ['ar' => 'تونغا', 'en' => 'Tonga', 'code' => '776'],
            ['ar' => 'ترينيداد وتوباغو', 'en' => 'Trinidad and Tobago', 'code' => '780'],
            ['ar' => 'تونس', 'en' => 'Tunisia', 'code' => '788'],
            ['ar' => 'تركيا', 'en' => 'Turkey', 'code' => '792'],
            ['ar' => 'جزر تركس وكايكوس', 'en' => 'Turks & Caicos Islands', 'code' => '796'],
            ['ar' => 'توفالو', 'en' => 'Tuvalu', 'code' => '798'],
            ['ar' => 'أوغندا', 'en' => 'Uganda', 'code' => '800'],
            ['ar' => 'أوكرانيا', 'en' => 'Ukraine', 'code' => '804'],
            ['ar' => 'الإمارات العربية المتحدة', 'en' => 'United Arab Emirates', 'code' => '784'],
            ['ar' => 'المملكة المتحدة', 'en' => 'United Kingdom', 'code' => '826'],
            ['ar' => 'الولايات المتحدة الأمريكية', 'en' => 'United States of America', 'code' => '840'],
            ['ar' => 'أوروغواي', 'en' => 'Uruguay', 'code' => '858'],
            ['ar' => 'أوزبكستان', 'en' => 'Uzbekistan', 'code' => '860'],
            ['ar' => 'فانواتو', 'en' => 'Vanuatu', 'code' => '548'],
            ['ar' => 'فنزويلا', 'en' => 'Venezuela', 'code' => '862'],
            ['ar' => 'فيتنام', 'en' => 'Vietnam', 'code' => '704'],
            ['ar' => 'جزر العذراء البريطانية', 'en' => 'British Virgin Islands', 'code' => '92'],
            ['ar' => 'جزر العذراء الأمريكية', 'en' => 'US Virgin Islands', 'code' => '850'],
            ['ar' => 'اليمن', 'en' => 'Yemen', 'code' => '887'],
            ['ar' => 'زامبيا', 'en' => 'Zambia', 'code' => '894'],
            ['ar' => 'زيمبابوي', 'en' => 'Zimbabwe', 'code' => '716'],
            ['ar' => 'تركمانستان', 'en' => 'Turkmenistan', 'code' => '795'],
            ['ar' => 'ساموا الأمريكية', 'en' => 'American Samoa', 'code' => '882'],
            ['ar' => 'جوادلوب', 'en' => 'Guadeloupe', 'code' => '312'],
            ['ar' => 'ليختنشتاين', 'en' => 'Liechtenstein', 'code' => '438'],
            ['ar' => 'مارتينيك', 'en' => 'Martinique', 'code' => '474'],
            ['ar' => 'ميانمار', 'en' => 'Myanmar', 'code' => '104'],
            ['ar' => 'قطر', 'en' => 'Qatar', 'code' => '634'],
            ['ar' => 'سانت بارتيليمي', 'en' => 'Saint Barthelemy', 'code' => '652'],
            ['ar' => 'جزر سليمان', 'en' => 'Solomon Islands', 'code' => '90'],
            ['ar' => 'سانت مارتن (الجزء الفرنسي)', 'en' => 'Saint Martin (French part)', 'code' => '663'],
            ['ar' => 'غينيا بيساو', 'en' => 'Guinea-Bissau', 'code' => '624'],
            ['ar' => 'سوريا', 'en' => 'Syria', 'code' => '760'],
            ['ar' => 'الصومال', 'en' => 'Somalia', 'code' => '252'],
            ['ar' => 'إيران', 'en' => 'Iran', 'code' => '98'],
            ['ar' => 'إيران', 'en' => 'Iran', 'code' => '364'],
            ['ar' => 'كوريا الشمالية', 'en' => 'North Korea', 'code' => '408'],
            ['ar' => 'لاوس', 'en' => 'Laos', 'code' => '418'],
            ['ar' => 'جمهورية مولدوفا', 'en' => 'Republic of Moldova', 'code' => '498'],
            ['ar' => 'سوازيلاند', 'en' => 'Swaziland', 'code' => '748'],
            ['ar' => 'أذربيجان', 'en' => 'Azerbaijan', 'code' => '31'],
            ['ar' => 'جزر كوك', 'en' => 'Cook Islands', 'code' => '184'],
            ['ar' => 'كوبا', 'en' => 'Cuba', 'code' => '192'],
            ['ar' => 'جنوب السودان', 'en' => 'South Sudan', 'code' => '728'],
            ['ar' => 'الجبل الأسود', 'en' => 'Montenegro', 'code' => '409'],
            ['ar' => 'يوغوسلافيا', 'en' => 'Yugoslavia', 'code' => '891'],
            ['ar' => 'بالاو', 'en' => 'Palau', 'code' => '585'],
        ];
    }
}
