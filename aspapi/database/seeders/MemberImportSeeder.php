<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Province;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberImportSeeder extends Seeder
{
    private array $provinceMap = [
        'BANTEN'                     => 'Banten',
        'DAERAH ISTIMEWA YOGYAKARTA' => 'DI Yogyakarta',
        'DKI JAKARTA'                => 'DKI Jakarta',
        'JAWA BARAT'                 => 'Jawa Barat',
        'JAWA TENGAH'                => 'Jawa Tengah',
        'JAWA TIMUR'                 => 'Jawa Timur',
        'KALIMANTAN BARAT'           => 'Kalimantan Barat',
        'KEPULAUAN RIAU'             => 'Kepulauan Riau',
        'LAMPUNG'                    => 'Lampung',
        'SULAWESI BARAT'             => 'Sulawesi Barat',
        'SULAWESI SELATAN'           => 'Sulawesi Selatan',
        'SUMATERA BARAT'             => 'Sumatera Barat',
        'SUMATERA UTARA'             => 'Sumatera Utara',
    ];

    /**
     * Mapping provinsi → slug region di tabel regions.
     * Hanya 8 provinsi yang sudah punya ASPAPI Daerah resmi.
     * Sisanya (Banten, Kalimantan Barat, Kepulauan Riau, Lampung, Sulawesi Barat)
     * belum punya → registered_by_region_id = null.
     */
    private array $regionMap = [
        'SUMATERA UTARA'             => 'sumatera-utara',
        'SUMATERA BARAT'             => 'sumatera-barat',
        'DKI JAKARTA'                => 'dki-jakarta',
        'JAWA BARAT'                 => 'jawa-barat',
        'JAWA TENGAH'                => 'jawa-tengah',
        'DAERAH ISTIMEWA YOGYAKARTA' => 'di-yogyakarta',
        'JAWA TIMUR'                 => 'jawa-timur',
        'SULAWESI SELATAN'           => 'sulawesi-selatan',
    ];

    public function run(): void
    {
        $provinces = Province::pluck('id', 'name');
        $regions   = Region::pluck('id', 'slug');

        // [province, name, email, occupation, join_year, member_number]
        $data = [
        ['BANTEN', 'SUHERDI', 'suherdi@unj.ac.id', 'Dosen', 2023, '36021230077'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'MUHYADI', 'muhyadi@uny.ac.id', 'Dosen', 2010, '34041100019'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'YULIANSAH', 'yuliansah@uny.ac.id', 'Dosen', 2023, '34021230150'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'DWIATMODJO BUDI SETYARTO', 'dwiatmodjo.budi@gmail.com', 'Dosen', 2023, '34711230062'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'SUMIYATI', 'sumi45369@gmail.com', 'Guru', 2015, '34012150135'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'RIQI DIANA', 'riqidianarina@gmail.com', 'Guru', 2015, '34012150136'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'RINA ARIYANI', 'rinaariy21@gmail.com', 'Guru', 2015, '34012150137'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'DWI ARTATI', 'dwiartati75@gmail.com', 'Guru', 2015, '34012150138'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'TINA HARYANTI', 'tina.haryanti@gmail.com', 'Guru', 2015, '34012150140'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'GANIS RETNO DEWANTI, S.PD', 'ganis.dewanti@gmail.com', 'Guru', 2024, '34022240142'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'FAUKI CAHYA', 'faukicahya08@gmail.com', 'Guru', 2023, '34041230143'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'PRISKA SINTA DEWI', 'priskasintadewi@gmail.com', 'Guru', 2024, '34032240146'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'YESI SOVI YULITA', 'yesisovi@smkmuhkarangmojo.sch.id', 'Guru', 2024, '34032240133'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'NUNING SIWI UTAMI', 'nuningsiwiutami@gmail.com', 'Guru', 2024, '34032240145'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'TUHADI', 'hadie014@gmail.com', 'Guru', 2015, '34011150148'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'SELLYANA NURUL AZIZAH, S.PD', 'sellyana0211@gmail.com', 'Guru', 2024, '34022240149'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'JOKO KUMORO', 'jokokum@uny.ac.id', 'Dosen', 2010, '34041100150'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'SRI LESTARI', 'srilestari.zizer@gmail.com', 'Guru', 2016, '34042160152'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'ISTI KISTIANANINGSIH', 'isti@uny.ac.id', 'Praktisi', 2018, '34022180151'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'NORNA ISTRI TEMAWATI', 'nornaistritemawati@gmail.com', 'Guru', 2020, '34022200158'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'FAILLA ROCHMAYANTI', 'faillarochmayanti@gmail.com', 'Guru', 2019, '34022190160'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'BADRUS SURYADI', 'badrussuryadi.72@gmail.com', 'Guru', 2019, '34021190160'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'IRVIAN NUR KHOLIS', 'irviankholis03@gmail.com', 'Guru', 2024, '34031240161'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'NIGIARTI WULANDARI, S.PD.', 'n.nigie22@gmail.com', 'Guru', 2020, '34032200162'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'EKA YULIANTA', 'ekayulianta5@gmail.com', 'Guru', 2020, '34041200163'],
        ['DAERAH ISTIMEWA YOGYAKARTA', 'PUJI ASTUTI', 'pujia3454@gmail.com', 'Guru', 2020, '34032200164'],
        ['DKI JAKARTA', 'MASTIUR TAMBUN', 'mastiurtambun83@guruku.smk.belajar.id', 'Guru', 2023, '31732230007'],
        ['DKI JAKARTA', 'IHSANA EL KHULUQO', 'ihsana.el@gmail.com', 'Dosen', 2012, '31732120014'],
        ['DKI JAKARTA', 'DINI SOFIYANI', 'dinisofiyani.ds@gmail.com', 'Guru', 2023, '31742230025'],
        ['DKI JAKARTA', 'VARINA VIOLETA', 'violetavarina@gmail.com', 'Guru', 2023, '31712230047'],
        ['DKI JAKARTA', 'DARMA RIKA SWARAMARINDA', 'darmarikas@gmail.com', 'Dosen', 2013, '31752130058'],
        ['DKI JAKARTA', 'M. JAMIL LATIEF', 'jamillatief8@gmail.com', 'Dosen', 2023, '31751230063'],
        ['DKI JAKARTA', 'ISTI ANDIRA RACHMADIYAH LATIEF', 'istiandira@gmail.com', 'Praktisi', 2022, '31752220064'],
        ['DKI JAKARTA', 'ARDILA MUSLIM', 'ardilam44@gmail.com', 'Praktisi', 2023, '31751230065'],
        ['DKI JAKARTA', 'MUNAWAROH', 'moena10@unj.ac.id', 'Dosen', 2019, '31752190082'],
        ['DKI JAKARTA', 'WIDYA PARIMITA', 'widya_parimita@unj.ac.id', 'Dosen', 2017, '31742170083'],
        ['DKI JAKARTA', 'GHIFARI AMINUDIN FAD\'LI', 'ghifariaf55@gmail.com', 'Praktisi', 2023, '31751230123'],
        ['DKI JAKARTA', 'ADY FAHRIL SUPARMAN', 'adyfahril@gmail.com', 'Praktisi', 2019, '31751190124'],
        ['DKI JAKARTA', 'ALIF MAHARRIZKI', 'maharrizki15@gmail.com', 'Praktisi', 2019, '31751190125'],
        ['DKI JAKARTA', 'SHAFWATUN NADA', 'shafwatunnada75@gmail.com', 'Guru', 2020, '31722200128'],
        ['DKI JAKARTA', 'RR SRI KARTIKOWATI,', 'tikokuliah75@gmail.com', 'Dosen', 2023, '31742230129'],
        ['DKI JAKARTA', 'ANGGRIAWAN OKTOBISONO', 'anggri413@gmail.com', 'Praktisi', 2019, '31751190224'],
        ['DKI JAKARTA', 'REZA ZATURRIZKI', 'zaturrizki89@gmail.com', 'Guru', 2024, '31751240226'],
        ['DKI JAKARTA', 'MUNAWAROH', 'apatenergigroup@gmail.com', 'Dosen', 2019, '31752190259'],
        ['DKI JAKARTA', 'SUSAN FEBRIANTINA', 'tuklsptriputrapersada@gmail.com', 'Dosen', 2019, '31752190260'],
        ['DKI JAKARTA', 'NURVANNY AGUSTYANA', 'vannyagustyana@gmail.com', 'Praktisi', 2025, '31712250261'],
        ['DKI JAKARTA', 'LATIFAH BUDIARSIH', 'latifah.budiarsih28@gmail.com', 'Guru', 2025, '31742250263'],
        ['DKI JAKARTA', 'MOHAMAD KHOLID PALALOI', 'ifahpal06@gmail.com', 'Guru', 2025, '31731250265'],
        ['DKI JAKARTA', 'MARSOFIYATI', 'ophie.three@gmail.com', 'Dosen', 2013, '31742130267'],
        ['DKI JAKARTA', 'JANA SANDRA', 'ukppg11250412@mail.unnes.ac.id', 'Dosen', 2025, '31752250270'],
        ['DKI JAKARTA', 'RESTI MEUTHIA', 'restimeuthia@smkn22jakarta.sch.id', 'Guru', 2025, '31752250271'],
        ['JAWA BARAT', 'MIRA VERANITA', 'mirave2198@gmail.com', 'Dosen', 2023, '32732230002'],
        ['JAWA BARAT', 'RASTO', 'rasto@upi.edu', 'Dosen', 2011, '32171110003'],
        ['JAWA BARAT', 'DAHLIA', 'dahlialetter@gmail.com', 'Dosen', 2023, '32012230005'],
        ['JAWA BARAT', 'LINA MARLINA', 'mlina6042@gmail.com', 'Guru', 2018, '32052180008'],
        ['JAWA BARAT', 'NANI IMANIYATI', 'naniimaniyati@upi.edu', 'Dosen', 2023, '32732230015'],
        ['JAWA BARAT', 'LINA MARLIANA', 'linamarliana1956@gmail.com', 'Praktisi', 2023, '32732230016'],
        ['JAWA BARAT', 'NUNUNG NURHAYATI', 'nungki1975@gmail.com', 'Guru', 2011, '32732110022'],
        ['JAWA BARAT', 'YANA SONJAYA', 'sonjayayana27@gmail.com', 'Dosen', 2023, '32041230023'],
        ['JAWA BARAT', 'TJUTJU YUNIARSIH', 'yuniarsih146@gamil.com', 'Dosen', 2011, '32172110027'],
        ['JAWA BARAT', 'TATANG TAHYAN', 'tatangsabelas@gmail.com', 'Guru', 2011, '32731110028'],
        ['JAWA BARAT', 'YAYAN HIMAWAN', 'yanshimawanbpi@gmail.com', 'Guru', 2012, '32731120029'],
        ['JAWA BARAT', 'ARIEF SUSANTO', 'arief@smkn3bandung.sch.id', 'Guru', 2015, '32731150030'],
        ['JAWA BARAT', 'BUDI SANTOSO', 'budisantoso@upi.edu', 'Dosen', 2011, '32771110027'],
        ['JAWA BARAT', 'EDI SURYADI', 'edi_suryadi@upi.edu', 'Dosen', 2011, '32171110031'],
        ['JAWA BARAT', 'ANGGIS WAHYU SAEPULLOH', 'anggis.aspapi@gmail.com', 'Guru', 2018, '32131180032'],
        ['JAWA BARAT', 'RESTI INDRIARTI', 'restiarti@upi.edu', 'Dosen', 2019, '32732190033'],
        ['JAWA BARAT', 'A. SOBANDI', 'ade@upi.edu', 'Dosen', 2010, '32041100034'],
        ['JAWA BARAT', 'DETI JUWITA', 'juwita.adetta@gmail.com', 'Guru', 2018, '32722180036'],
        ['JAWA BARAT', 'DEWI SETIATI', 'ibudewi1612@gmail.com', 'Praktisi', 2012, '32172120037'],
        ['JAWA BARAT', 'HADY SITI HADIJAH', 'hady@upi.edu', 'Dosen', 2011, '32732110038'],
        ['JAWA BARAT', 'SUWATNO', 'suwatno@upi.edu', 'Dosen', 2011, '32171110039'],
        ['JAWA BARAT', 'ADMAN', 'adman@upi.edu', 'Dosen', 2011, '32041110042'],
        ['JAWA BARAT', 'UEP TATANG SONTANI,', 'ueptatangsontani38@gmail.com', 'Praktisi', 2011, '32171110043'],
        ['JAWA BARAT', 'FIFIT HADIATY', 'fifithadiaty@asmkencana.ac.id', 'Dosen', 2015, '32732150049'],
        ['JAWA BARAT', 'AZIZ MUHAMMAD', 'aziz.muhammad8910@gmail.com', 'Dosen', 2020, '32131200049'],
        ['JAWA BARAT', 'SYAFIANI DESIANA GARTINI', 'syafianidesiana63822@gmail.com', 'Guru', 2011, '32732110052'],
        ['JAWA BARAT', 'RINI INTANSARI MEILANI', 'intanmusthafa@upi.edu', 'Dosen', 2012, '32172120054'],
        ['JAWA BARAT', 'HENDRI WINATA', 'hendri@upi.edu', 'Dosen', 2011, '32731110055'],
        ['JAWA BARAT', 'MELASARI', 'alif180210@gmail.com', 'Guru', 2018, '32082180059'],
        ['JAWA BARAT', 'KOKOM KOMALASARI', 'kokom.sukmana77@gmail.com', 'Guru', 2014, '32112140060'],
        ['JAWA BARAT', 'NIA KURNIASIH', 'niakurniasih.dharmapertiwi@gmail.com', 'Guru', 2023, '32172230066'],
        ['JAWA BARAT', 'MILA NOVALIA SANTONO', 'milanovalia82@gmail.com', 'Guru', 2023, '32092230069'],
        ['JAWA BARAT', 'SITI NURUL HASANAH', 'nurulsiti68@gmail.com', 'Guru', 2017, '32032170072'],
        ['JAWA BARAT', 'TRIWAHYUNINGSIH', 'triwahyuningsihukk@gmail.com', 'Guru', 2015, '32142150070'],
        ['JAWA BARAT', 'TINA SUNDARI', 'tina.sundari73@gmail.com', 'Guru', 2023, '32172230073'],
        ['JAWA BARAT', 'DIAN SITI MASITOH', 'diansitimasitoh@upi.edu', 'Praktisi', 2018, '32732180076'],
        ['JAWA BARAT', 'CHRISTIAN WIRADENDI WOLOR', 'christianwiradendi@unj.ac.id', 'Dosen', 2019, '32751190079'],
        ['JAWA BARAT', 'SUSAN FEBRIANTINA', 'susanfebriantina@unj.ac.id', 'Dosen', 2017, '32162170081'],
        ['JAWA BARAT', 'NIAR WINIARTI', 'winiartiniar@gmail.com', 'Guru', 2023, '32052230085'],
        ['JAWA BARAT', 'ENDANG SUPARDI', 'endang-supardi@upi.edu', 'Dosen', 2012, '32171120087'],
        ['JAWA BARAT', 'NINGRUM SURYATININGSIH', 'niniekarraz@gmail.com', 'Guru', 2011, '32772110087'],
        ['JAWA BARAT', 'WAWAN SUNARYA', 'wawan.sunarya@smkn3bandung.sch.id', 'Guru', 2012, '32731120088'],
        ['JAWA BARAT', 'NANA ANGGRAENI', 'nanaanggraeni98@guru.smk.belajar.id', 'Guru', 2017, '32132170089'],
        ['JAWA BARAT', 'YADI SUPRIYADI USMAN', 'yadiwungkul@yahoo.com', 'Guru', 2012, '32121120090'],
        ['JAWA BARAT', 'DIAN AZIZAH HASIBUAN', 'adamabqary8@gmail.com', 'Guru', 2023, '32022230091'],
        ['JAWA BARAT', 'ASEP SAPRUDIN', 'asepeuisros18@gmail.com', 'Guru', 2012, '32081120092'],
        ['JAWA BARAT', 'DWI HARTATI', 'dwihartati482@gmail.com', 'Guru', 2022, '32012220093'],
        ['JAWA BARAT', 'TINI MARTINI', 'niemartini@yahoo.com', 'Dosen', 2012, '32732120094'],
        ['JAWA BARAT', 'NOOR CHOTIMAH TARDENAMI', 'noorchtardenami@gmail.com', 'Guru', 2022, '32172220095'],
        ['JAWA BARAT', 'ARDI M NOER', 'rdnoer@gmail.com', 'Praktisi', 2019, '32171190096'],
        ['JAWA BARAT', 'ANIK ANDIYANI', 'anikandiyani@gmail.com', 'Guru', 2023, '32122230097'],
        ['JAWA BARAT', 'SHOLIHAT NURUL INSANI', 'shoniafaqhot@gmail.com', 'Guru', 2022, '32052220098'],
        ['JAWA BARAT', 'MILA NOVALIA SANTONO', 'milanovalia227@gmail.com', 'Guru', 2023, '32092230100'],
        ['JAWA BARAT', 'ADE NINA HIDAYAH', 'adeninahidayah@gmail.com', 'Guru', 2022, '32782220102'],
        ['JAWA BARAT', 'ANNA ASTRIANA', '2512astrianna@gmail.com', 'Guru', 2018, '32172180104'],
        ['JAWA BARAT', 'CHANDRA HENDRIYANI', 'chandrahendriyani@yahoo.com', 'Dosen', 2018, '32732180105'],
        ['JAWA BARAT', 'DWI HANDAYANI', 'dwihandayani682@gmail.com', 'Guru', 2018, '32012180106'],
        ['JAWA BARAT', 'WAWANG KURNIAWANGSIH', 'wawangks@gmail.com', 'Guru', 2011, '32732110107'],
        ['JAWA BARAT', 'TOTO JUNARTO', 'toto.junarto4307@gmail.com', 'Guru', 2015, '32081150108'],
        ['JAWA BARAT', 'GINA APRILITA SUSANTY', 'g.aprilita@gmail.com', 'Praktisi', 2019, '32172190109'],
        ['JAWA BARAT', 'YOSEP HERNAWAN', 'yosep.hernawan@upi.edu', 'Dosen', 2020, '32041200110'],
        ['JAWA BARAT', 'ABI SOPYAN FEBRIANTO', 'abisopyan@upi.edu', 'Dosen', 2020, '32171200111'],
        ['JAWA BARAT', 'TOYIB ARYANTO', 'toyibaryanto@gmail.com', 'Guru', 2011, '32171120112'],
        ['JAWA BARAT', 'UEP TATANG SONTANI', 'ueptatangsontani38@gmail.ccom', 'Praktisi', 2012, '32171120113'],
        ['JAWA BARAT', 'FAHMI JAHIDAH ISLAMY', 'fahmiislamy10@upi.edu', 'Dosen', 2020, '32172200114'],
        ['JAWA BARAT', 'DIAN ADDINNA', 'dian.addinna@upi.edu', 'Dosen', 2020, '32172200115'],
        ['JAWA BARAT', 'RISKE FALDESIANI', 'riskefaldesiani@upi.edu', 'Dosen', 2020, '32732200116'],
        ['JAWA BARAT', 'FAUZIYYAH RAMDHANI', 'fauziyyah.ramdhani18@gmail.com', 'Praktisi', 2023, '32732230117'],
        ['JAWA BARAT', 'SANTI NURJANAH', 'santinur@upi.edu', 'Praktisi', 2019, '32042190118'],
        ['JAWA BARAT', 'TUTIK INAYATI', 'tutik.inayati@upi.edu', 'Dosen', 2023, '32172230119'],
        ['JAWA BARAT', 'IRMA RUSYDA ELMAGHFIRATUNNIDA', 'irmaelmaghfiratunnida05@guru.smk.belajar.id', 'Guru', 2018, '32762180120'],
        ['JAWA BARAT', 'JANAH SOJANAH', 'janahsojanah@upi.edu', 'Dosen', 2011, '32732110121'],
        ['JAWA BARAT', 'WIDI ANGGRAENI', 'widianggraeni@upi.edu', 'Praktisi', 2019, '32732190122'],
        ['JAWA BARAT', 'UUS FIRDAUS', 'uusfirdaus695@gmail.com', 'Dosen', 2023, '32051230127'],
        ['JAWA BARAT', 'WAHYU KAZEKA PRIANTO', 'tugaskamousunj@gmail.com', 'Praktisi', 2024, '32161240155'],
        ['JAWA BARAT', 'BETTY IRAWATI', 'bettyirawati8@gmail.com', 'Guru', 2023, '32772230139'],
        ['JAWA BARAT', 'RENI RATNANINGSIH', 'reniratnaningsih29@gmail.com', 'Guru', 2024, '32132240201'],
        ['JAWA BARAT', 'HANNI FAULINE HARDIYANTI', 'hannifauline8775@gmail.com', 'Guru', 2015, '32042150202'],
        ['JAWA BARAT', 'N. AN AN NITA ANITA S', 'anita1234smk@gmail.com', 'Guru', 2024, '32042240216'],
        ['JAWA BARAT', 'SUSI NUR\'AENI', 'susinur86@yahoo.co.id', 'Guru', 2024, '32112240218'],
        ['JAWA BARAT', 'ELA JULAEHA', 'elajulaeha1976@gmail.com', 'Dosen', 2024, '32732240225'],
        ['JAWA BARAT', 'KURNIASYIH', 'kurniasyih11@guru.smk.belajar.id', 'Guru', 2023, '32752230227'],
        ['JAWA BARAT', 'BADRIYAH', 'badriyah_lp3i@yahoo.co.id', 'Dosen', 2024, '32762240232'],
        ['JAWA BARAT', 'NIHAYATUL ADAWIYAH', 'adawiyahpuput@asmkencana.ac.id', 'Dosen', 2024, '32172240235'],
        ['JAWA BARAT', 'RATNA WULAN DARI', 'ratnaa.wuland@gmail.com', 'Praktisi', 2024, '32732240236'],
        ['JAWA BARAT', 'ELY SUCI BUDIANTI', 'elysucibudianti30@gmail.com', 'Guru', 2025, '32122250244'],
        ['JAWA BARAT', 'CHRISTINE RAMBING', 'christinerambing77@gmail.com', 'Guru', 2025, '32762250246'],
        ['JAWA BARAT', 'DESILIA PURNAMA DEWI', 'dosen00810@unpam.ac.id', 'Dosen', 2025, '32762250249'],
        ['JAWA BARAT', 'SAMBAS ALI MUHIDIN', 'sambas2167@gmail.com', 'Dosen', 2025, '32731250262'],
        ['JAWA BARAT', 'NUR AINI PARWITASARI', 'ainiparwitasari@gmail.com', 'Dosen', 2025, '32732250268'],
        ['JAWA BARAT', 'EMA AMBIAPURI', 'ambiapurie@gmail.com', 'Dosen', 2025, '32732250269'],
        ['JAWA BARAT', 'MAYA SOFIANA', 'maya72sofiana@gmail.com', 'Dosen', 2020, '32162200272'],
        ['JAWA TENGAH', 'AHMAD SUYANTO', 'suyantoahmads.67@gmail.com', 'Guru', 2023, '33221230001'],
        ['JAWA TENGAH', 'SUTIRMAN', 'sutirman@uny.ac.id', 'Dosen', 2024, '33101110006'],
        ['JAWA TENGAH', 'AHMAD SAEROJI', 'saeroji@mail.unnes.ac.id', 'Dosen', 2017, '33741170018'],
        ['JAWA TENGAH', 'WIEDY MURTINI', 'idik_53@yahoo.co.id', 'Dosen', 2010, '33102100044'],
        ['JAWA TENGAH', 'PATNI NINGHARDJANTI', 'buning@fkip.uns.ac.id', 'Dosen', 2010, '33132100051'],
        ['JAWA TENGAH', 'SUSANTININGRUM', 'susantiningrum@staff.uns.ac.id', 'Dosen', 2018, '33132180056'],
        ['JAWA TENGAH', 'TEGUH HARDI RAHARJO', 'teguh.hardi@mail.unnes.ac.id', 'Dosen', 2023, '33741230068'],
        ['JAWA TENGAH', 'HANUM KARTIKASARI', 'hanumkartikasari31@staff.uns.ac.id', 'Dosen', 2023, '33142230075'],
        ['JAWA TENGAH', 'AHMAD JAENUDIN', 'ahmadjaenudin@mail.unnes.ac.id', 'Dosen', 2023, '33741230101'],
        ['JAWA TENGAH', 'LOLA KURNIA PITALOKA', 'lolakp@mail.unnes.ac.id', 'Dosen', 2023, '33742230103'],
        ['JAWA TENGAH', 'KRISTIN WAHYUNI', 'kristinwahyuni7@gmail.com', 'Guru', 2024, '33202230130'],
        ['JAWA TENGAH', 'DWI LESTARI RENANINGTYAS', 'lestariningtyas01@gmail.com', 'Guru', 2024, '33202230131'],
        ['JAWA TENGAH', 'TUSYANAH', 'tusyanah@mail.unnes.ac.id', 'Dosen', 2023, '33232230160'],
        ['JAWA TENGAH', 'SEPTI KURNIAWATI', 'septykurnia23@gmail.com', 'Guru', 2024, '33742240132'],
        ['JAWA TENGAH', 'TRI MINARNI', 'minarnitrie@gmail.com', 'Guru', 2024, '33742240134'],
        ['JAWA TENGAH', 'NUR CHOIRUL AFIF', 'daniah363600@gmail.com', 'Dosen', 2024, '33021240156'],
        ['JAWA TENGAH', 'SOFIATUL KHOTIMAH', 'taniahayday29@gmail.com', 'Dosen', 2024, '33022240157'],
        ['JAWA TENGAH', 'RETNO KURNIASIH', 'febby363600@gmail.com', 'Dosen', 2024, '33022240154'],
        ['JAWA TENGAH', 'LUSI SUWANDARI', 'janurjang17@gmail.com', 'Dosen', 2024, '33022240153'],
        ['JAWA TENGAH', 'KARTIKA WIDIYAH ASTUTI', 'kartikawias@gmail.com', 'Guru', 2024, '33202240147'],
        ['JAWA TENGAH', 'ANITA RAHAYU', 'anitarahayu740@gmail.com', 'Guru', 2024, '33082240204'],
        ['JAWA TENGAH', 'SUKANAH', 'kanahdodi2@gmail.com', 'Guru', 2024, '33172240205'],
        ['JAWA TENGAH', 'SRI HASTUTI', 'shastuti71@gmail.com', 'Guru', 2024, '33212240206'],
        ['JAWA TENGAH', 'SRI MULAT SETYANINGSIH', 'srisetyaningsih65@guru.smk.belajar.id', 'Guru', 2024, '33112240207'],
        ['JAWA TENGAH', 'ASSOFIQ DWI KURNIAWAN', 'assofiq.dwik@gmail.com', 'Guru', 2023, '33081230208'],
        ['JAWA TENGAH', 'MUHAMAD NUKHA MURTADLO', 'nukhamurtadlo@mail.unnes.ac.id', 'Dosen', 2023, '33191230209'],
        ['JAWA TENGAH', 'CHAIRUL HUDA ATMA DIRGATAMA', 'chairul_huda@staff.uns.ac.id', 'Dosen', 2018, '33051180211'],
        ['JAWA TENGAH', 'HENGKY PRAMUSINTO', 'hpramusinto@mail.unnes.ac.id', 'Dosen', 2019, '33741190212'],
        ['JAWA TENGAH', 'ISMIYATI', 'ismiyati@mail.unnes.ac.id', 'Dosen', 2019, '33742190213'],
        ['JAWA TENGAH', 'AGUNG KUSWANTORO', 'agungbinmadik@mail.unnes.ac.id', 'Dosen', 2019, '33741190214'],
        ['JAWA TENGAH', 'NINA OKTARINA', 'ninaoktarina@mail.unnes.ac.id', 'Dosen', 2019, '33742190215'],
        ['JAWA TENGAH', 'ANIS SUSANTI', 'anis.susanti917@gmail.com', 'Dosen', 2024, '33042240217'],
        ['JAWA TENGAH', 'ENDANG SULISTIYANI', 'endangsulis15@polines.ac.id', 'Dosen', 2024, '33742240219'],
        ['JAWA TENGAH', 'IRIN MIRRAH LUTHFIA', 'irinluthfia@polines.ac.id', 'Dosen', 2024, '33742240237'],
        ['JAWA TENGAH', 'MONA INAYAH PRATIWI', 'monainayah@gmail.com', 'Dosen', 2024, '33742240238'],
        ['JAWA TENGAH', 'EVA PURNAMASARI', 'eva.purnamasari@polines.ac.id', 'Dosen', 2024, '33222240239'],
        ['JAWA TENGAH', 'ARIF WAHYU WIRAWAN', 'arifwahyu@mail.unnes.ac.id', 'Dosen', 2017, '33051170250'],
        ['JAWA TENGAH', 'AULIA PRIMA KHARISMAPUTRA', 'aulia@mail.unnes.ac.id', 'Dosen', 2023, '33111230251'],
        ['JAWA TENGAH', 'DWI ASTARANI ASLINDAR', 'dwi.astarani@unsoed.ac.id', 'Dosen', 2025, '33752250255'],
        ['JAWA TENGAH', 'ARIA MULYAPRADANA', 'ariamulyapradana@gmail.com', 'Dosen', 2025, '33021250256'],
        ['JAWA TENGAH', 'MAFTURRAHMAN', 'mafturrahmansos@gmail.com', 'Dosen', 2025, '33261250257'],
        ['JAWA TENGAH', 'ARY DWI ANJARINI', 'ap.dekabita@gmail.com', 'Dosen', 2025, '33262250258'],
        ['JAWA TENGAH', 'SUTIRMAN', 'sutirman@gmail.com', 'Dosen', 2012, '33101120275'],
        ['JAWA TENGAH', 'FAHMI ULIN NI\'MAH', 'fahmiulinnimah@gmail.com', 'Dosen', 2026, '33192260276'],
        ['JAWA TIMUR', 'MEYLIA ELIZABETH RANU', 'elizabethranu@gmail.com', 'Dosen', 2011, '35782110040'],
        ['JAWA TIMUR', 'NOVI TRISNAWATI', 'novitrisnawati@unesa.ac.id', 'Dosen', 2017, '35782170041'],
        ['JAWA TIMUR', 'ANDI BASUKI', 'andi.basuki.fe@um.ac.id', 'Dosen', 2018, '35731180053'],
        ['JAWA TIMUR', 'TRIESNINDA PAHLEVI', 'triesnindapahlevi@unesa.ac.id', 'Dosen', 2014, '35152140057'],
        ['JAWA TIMUR', 'RAHMAT YULIAWAN', 'rahmat.yuliawan@vokasi.unair.ac.id', 'Dosen', 2023, '35781230071'],
        ['JAWA TIMUR', 'MAULANA AMIRUL ADHA', 'maulanaamirul@unj.ac.id', 'Dosen', 2023, '35141230078'],
        ['JAWA TIMUR', 'CHOIRUL ANAM', 'choirul.anam.fe@um.ac.id', 'Dosen', 2023, '35071230099'],
        ['JAWA TIMUR', 'FARIJ IBADIL MAULA', 'farijmaula@unesa.ac.id', 'Dosen', 2023, '35751230203'],
        ['JAWA TIMUR', 'FEBRIKA YOGIE HERMANTO', 'febrikahermanto@unesa.ac.id', 'Dosen', 2024, '35151240210'],
        ['JAWA TIMUR', 'IRSYADUL IBAD', 'irsyadulibad@staff.uns.ac.id', 'Dosen', 2024, '35151240220'],
        ['JAWA TIMUR', 'SHANTI IKE WARDANI', 'shanti@akb.ac.id', 'Dosen', 2024, '35722240240'],
        ['JAWA TIMUR', 'MOH. BADRUT TAMAM', 'mohbadruttamam28@gmail.com', 'Praktisi', 2024, '35731240241'],
        ['JAWA TIMUR', 'RIDHO MUARIEF', 'ridho.muarief@pnm.ac.id', 'Dosen', 2025, '35771250243'],
        ['KALIMANTAN BARAT', 'DHIDIK APRIYANTO', 'dhidikapriyanto@gmail.com', 'Dosen', 2022, '61711220084'],
        ['KALIMANTAN BARAT', 'INDAH SULISDIANI', 'indah.sulisdiani@fisip.untan.ac.id', 'Dosen', 2025, '61712250247'],
        ['KALIMANTAN BARAT', 'RASIDAR', 'rasidar@fisip.untan.ac.id', 'Dosen', 2025, '61712250248'],
        ['KALIMANTAN BARAT', 'AGISNA MAR\'ATANA', 'agisnmr27@gmail.com', 'Dosen', 2025, '61712250252'],
        ['KALIMANTAN BARAT', 'NURUL KHOTIMAH', 'nurulkhotimahrahman@gmail.com', 'Dosen', 2025, '61122250254'],
        ['KALIMANTAN BARAT', 'DITA PRATIWI', 'ditapratiwi@fisip.untan.ac.id', 'Dosen', 2025, '61712250253'],
        ['KEPULAUAN RIAU', 'ENJANG SUHAEDIN', 'ensfillah19@gmail.com', 'Guru', 2023, '21711230067'],
        ['LAMPUNG', 'MEDIYA DESTALIA', 'mediya.destalia@fisip.unila.ac.id', 'Dosen', 2023, '18722230221'],
        ['LAMPUNG', 'HANI DAMAYANTI APRILIA', 'hani.damayanti@fisip.unila.ac.id', 'Dosen', 2024, '18712240222'],
        ['LAMPUNG', 'AKGIS CAHYA NINGTIAS', 'akgiscahya02@gmail.com', 'Dosen', 2024, '18712240223'],
        ['LAMPUNG', 'PRASETYA NUGERAHA', 'prasetya.nugeraha18@gmail.com', 'Dosen', 2024, '18711240228'],
        ['LAMPUNG', 'FENNY SAPTIANI', 'fenotsap@gmail.com', 'Dosen', 2024, '18712240229'],
        ['LAMPUNG', 'JENI WULANDARI', 'jeni.wulandari@fisip.unila.ac.id', 'Dosen', 2024, '18712240230'],
        ['LAMPUNG', 'GITA PARAMITA DJAUSAL', 'gita.djausal@fisip.unila.ac.id', 'Dosen', 2024, '18712240231'],
        ['LAMPUNG', 'DAMAYANTI', 'damayanti.1981@fisip.unila.ac.id', 'Dosen', 2024, '18712240233'],
        ['LAMPUNG', 'WINDA SEPTIANI', 'windaseptiani.tr@gmail.com', 'Dosen', 2024, '18712240234'],
        ['SULAWESI BARAT', 'AHMAD SULTAN', 'ahmadsultanpolman123@gmail.com', 'Guru', 2023, '76041230004'],
        ['SULAWESI SELATAN', 'SIRAJUDDIN SALEH', 'sirajuddinsaleh@unm.ac.id', 'Dosen', 2018, '73711180009'],
        ['SULAWESI SELATAN', 'SITTI HARDIYANTI ARHAS', 'a.arhas.03@gmail.com', 'Dosen', 2020, '73712200010'],
        ['SULAWESI SELATAN', 'MUH. DARWIS', 'muh.darwis@unm.ac.id', 'Dosen', 2011, '73061110011'],
        ['SULAWESI SELATAN', 'RISMA NISWATY', 'risma.niswaty@unm.ac.id', 'Dosen', 2011, '73062110020'],
        ['SULAWESI SELATAN', 'MULYADI', 'adhy.375@gmail.com', 'Praktisi', 2022, '73711220024'],
        ['SULAWESI SELATAN', 'ZAINUDDIN', 'zainuddinzet@gmail.com', 'Guru', 2021, '73711210035'],
        ['SULAWESI SELATAN', 'DRA. IMASITA, M.SI.', 'imasita@poliupg.ac.id', 'Dosen', 2020, '73712200046'],
        ['SULAWESI SELATAN', 'DRS. HIRMAN, M.SI.', 'hirman@poliupg.ac.id', 'Dosen', 2020, '73711200061'],
        ['SULAWESI SELATAN', 'A. ANNA RIFAI', 'andianna1969@gmail', 'Guru', 2023, '73712230074'],
        ['SULAWESI SELATAN', 'SUFRIADI', 'adhylopez@gmail.com', 'Guru', 2018, '73071180126'],
        ['SULAWESI SELATAN', 'ISMAIL', 'ismail7907@unm.ac.id', 'Dosen', 2025, '73711250245'],
        ['SUMATERA BARAT', 'RINO', 'rinopekon@fe.unp.ac.id', 'Dosen', 2022, '13711220013'],
        ['SUMATERA BARAT', 'ARMIDA SILVIA', 'mimiasriel@gmail.com', 'Dosen', 2010, '13712100012'],
        ['SUMATERA BARAT', 'YUHENDRI LEO VRISTA', 'yvrista@yahoo.com', 'Dosen', 2014, '13711140017'],
        ['SUMATERA BARAT', 'ARMIATI', 'armiati@fe.unp.ac.id', 'Dosen', 2014, '13712140021'],
        ['SUMATERA UTARA', 'KHAIRANI ALAWIYAH MATONDANG', 'alawiyah@unimed.ac.id', 'Dosen', 2018, '12092180050'],
        ['SUMATERA UTARA', 'DODI PRAMANA', 'dodipramana@unimed.ac.id', 'Dosen', 2018, '12071180141'],
        ];

        $imported = 0;
        $skipped  = 0;

        foreach ($data as [$province, $name, $email, $occupation, $joinYear, $memberNumber]) {

            if (User::where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            DB::transaction(function () use (
                $province, $name, $email, $occupation,
                $joinYear, $memberNumber,
                $provinces, $regions, &$imported
            ) {
                $user = User::create([
                    'name'           => $name,
                    'email'          => $email,
                    'password'       => Hash::make('aspapi2024'),
                    'role'           => 'anggota',
                    'email_verified' => true,
                ]);

                $provinceName = $this->provinceMap[strtoupper(trim($province))] ?? null;
                $provinceId   = $provinceName ? ($provinces[$provinceName] ?? null) : null;

                $regionSlug = $this->regionMap[strtoupper(trim($province))] ?? null;
                $regionId   = $regionSlug ? ($regions[$regionSlug] ?? null) : null;

                $memberType = match (strtolower($occupation)) {
                    'praktisi' => 'luar_biasa',
                    default    => 'biasa',
                };

                Member::create([
                    'user_id'                 => $user->id,
                    'full_name'               => $name,
                    'email'                   => $email,
                    'institution'             => $occupation,
                    'member_type'             => $memberType,
                    'registration_type'       => 'lama',
                    'claims_old_member'       => true,
                    'claimed_join_year'       => $joinYear,
                    'member_number'           => $memberNumber
                        ? str_pad((string) $memberNumber, 11, '0', STR_PAD_LEFT)
                        : null,
                    'province_id'             => $provinceId,
                    'registered_by_region_id' => $regionId,
                    'biodata_status'          => 'verified',
                    'status'                  => 'pending',
                    'registered_at'           => $joinYear
                        ? Carbon::create($joinYear, 1, 1)
                        : now(),
                ]);

                $imported++;
            });
        }

        $this->command->info("Selesai: {$imported} diimport, {$skipped} dilewati (sudah ada).");
        $this->command->warn("Password default semua akun: aspapi2024");
        $this->command->warn("Anggota tinggal login dan upload bukti iuran tahunan Rp 120.000.");
    }
}