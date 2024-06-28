<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *6
     * @return void
     */
    public function run()
    {
        /* $books = [

          foreach ($books as $book) {
              Book::create($book);
          }*/

        $books = [
            //novels
            [
                'title_en' => 'Amerata',
                'title_ar' => 'أماريتا',
                'file' => 'أماريتا',
                'cover' => 'amarita',
                'author_name_en' =>'Amro abd alhamied',
                'author_name_ar' => 'عمرو عبد الحميد',
                'points' => 0,
                'description_en'=> '',
                'description_ar' => '
        لم أرَ من قبل خوف وجوه أهل زيكولا مثلما كنت أراه في تلك اللحظات أسفل أنوار المشاعل، زيكولا القوية التي تباهي أهلها دومًا بقوتها، باتوا عند أول اختبار حقيقي وجوهًا ذابلة مصدومة تخشى لحظاتها القادمة، أرض الرقص والاحتفالات لم تعد إلا أرض الخوف، أعلم أنهم يلعنون أسيل في داخلهم منذ تسربت إليهم الأخبار أنها سبب مايحدث لهم ، لكنهم قد تجاهلوا عمدًا أنهم من اقتنصوا ذكاءها كاملًا دون أن تضر واحدًا منهم يومًا ..',
                'total_pages' => 324,
                'type_id' => 1,
            ],

            //Islamic
            [
                'title_en' => 'these are the quran messages who will received it ',
                'title_ar' => 'هذه رسالات القرآن فمن يتلقاها',
                'file' => 'هذه رسالات القرآن فمن يتلقاها',
                'cover' => 'رسالته',
                'author_name_en' => 'Faried al-ansary ',
                'author_name_ar' => 'فريد الأنصاري',
                'points' => 5,
                'description_en'=> '',
                'description_ar' => 'هذه رسالات قرآنية، قد بعث بها الشيخ فريد الأنصاري -رحمه الله- قبيل رحيله بقليل إلى دار البقاء إلى أتباعه ومحبيه عبر موقعه الفطرية، إذ كان يتواصل من خلالها معهم،حاثا اياهم على التمسك بحبل القرآن الممدود من السماء، الذي طرفه بيد الله وطرفه الآخر بيد من أخذ به من عباد الله الصالحين.',
                'total_pages' => 107,
                'type_id' => 2,
            ],
    ];
          /*  //Children
            [
                'title' => 'العجوز والعصفور',
                'file' => 'العجوز والعصفور',
                'cover' => 'العجوز',
                'author_name' => 'محاسن جادو',
                'points' => 0,
                'description' => '
        يا أصدقاء احذروا من الطمع فإنه أضاع على العجوز طعامه وعلى العصفور بيته...',
                'total_pages' => 19,
                'type_id' => 3,
            ],

            //ٍScientific
            [
                'title' => 'كيمياء البروتينات',
                'file' => 'كيمياء البروتينات',
                'cover' => 'كيمياء',
                'author_name' => 'رياض عبد الكريم حمد',
                'points' => 10,
                'description' => 'كتاب يأخذ بيدك الى لب الكيمياء لنفهم سوية كيف تتكون المواد وسنتكلم عن البروتينات ونغوص في تفاصيلها العلمية الدقيقة',
                'total_pages' => 47,
                'type_id' => 4,
            ],

            //Horror
            [
                'title' => 'ابتسم فأنت ميت',
                'file' => 'ابتسم فأنت ميت',
                'cover' => 'ابتسم',
                'author_name' => 'حسن الجندي',
                'points' => 0,
                'description' => 'تدور أحداث رواية أبتسم فأنت ميت حول شقة مهجورة ونتيجة أن صاحب الشقة مهاجر خارج مصر بداء بواب العماره تأجير تلك الشقة بسعر مخفض للزبأن، وفي تلك الشقة تحدث أمور غير طبيعيه، تم استأجرها في البداية من قبل ثلاثة وهما أمجد وسيد وصادق وهما طلاب في المرحلة الجامعية، ثم يتم استاجرها بعد ذلك لرجل وزجتة لا ينجبان، وبعدهم يتم تأجيرها لمصور وكان يريد ان يجعلها محل تصوير له، وفي اخر الامر يتم تأجيرها الي طبيب نفسي حتي يتمكن من حل الأحداث والألغاز الغريبة التي حدثت لتلك الشخصيات التي قامة بتأجير هذة الشقة.',
                'total_pages' => 307,
                'type_id' => 5,
            ],

            //Human Development
            [
                'title' => 'المفاتيح العشرة للنجاح',
                'file' => 'المفاتيح العشرة للنجاح',
                'cover' => 'muftah',
                'author_name' => 'إبراهيم الفقي',
                'points' => 50,
                'description' => '
        يعتبر هذا الكتاب من أقوى الكتب العالمية في التنمية البشرية، ولذلك فقد بيعت منه أكثر من 1.000.000 نسخة في العالم, ومعه سيأخذ الكاتب القراء إلى اكتشافات يرشد فيها إلى الطريقة التي تجعله متحمساً في الحال, ويكون لديه الخطة التي تمكنه من الاحتفاظ بهذا الحماس, كما تمد القارئ بالطاقة المتأججة في أي لحظة وترفع من درجة ثقته بنفسه وقوته الذاتية. كما فيه تعليم لأسرار قوة الالتزام والفعل والتفكير الإيجابي والتصور وكيفية استخدامهم في حياته اليومية لتبلغ الدرجة القصوى من النجاح.',
                'total_pages' => 63,
                'type_id' => 6,
            ],

        ];*/

        foreach ($books as $book) {

            $existingBook = Book::where('title_en', $book['title_en'])->first();


            if ($existingBook) {
                continue;
            }
            $filePath = '/books/files/' . $book['file'] . '.pdf';
            $coverPath = '/books/cover_images/' . $book['cover'] . '.jpg';

            Book::create([
                'title_en' => $book['title_en'],
                'title_ar' => $book['title_ar'],
                'file' => $filePath,
                'cover' => $coverPath,
                'author_name_en' => $book['author_name_en'],
                'author_name_ar' => $book['author_name_ar'],
                'points' => $book['points'],
                'description_en' => $book['description_en'],
                'description_ar' => $book['description_ar'],
                'total_pages' => $book['total_pages'],
                'type_id' => $book['type_id'],
            ]);
        }
    }

}



