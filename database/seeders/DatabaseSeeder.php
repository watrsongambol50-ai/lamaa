<?php
namespace Database\Seeders;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Service::truncate();
        Service::insert([
            // ثياب تقليدية
            ['name_ar'=>'ثوب أبيض',      'name_en'=>'White Thobe',       'icon'=>'thobe',    'category'=>'ثياب_تقليدية','base_price'=>4, 'description'=>'express:9', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'ثوب ملون',       'name_en'=>'Colored Thobe',     'icon'=>'thobe',    'category'=>'ثياب_تقليدية','base_price'=>5, 'description'=>'express:7', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'ثوب صوف',        'name_en'=>'Wool Thobe',        'icon'=>'thobe',    'category'=>'ثياب_تقليدية','base_price'=>7, 'description'=>'express:10','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'غترة',           'name_en'=>'Ghutra',            'icon'=>'head',     'category'=>'ثياب_تقليدية','base_price'=>4, 'description'=>'express:6', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'شماغ',           'name_en'=>'Shimagh',           'icon'=>'head',     'category'=>'ثياب_تقليدية','base_price'=>4, 'description'=>'express:8', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'طاقية',          'name_en'=>'Taqia (Cap)',       'icon'=>'cap',      'category'=>'ثياب_تقليدية','base_price'=>1, 'description'=>null,        'created_at'=>now(),'updated_at'=>now()],
            // ملابس عامة
            ['name_ar'=>'قميص',           'name_en'=>'Shirt',             'icon'=>'shirt',    'category'=>'ملابس_عامة','base_price'=>4,  'description'=>'express:6', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'بنطلون',         'name_en'=>'Pants',             'icon'=>'pants',    'category'=>'ملابس_عامة','base_price'=>4,  'description'=>'express:6', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'تي شرت',         'name_en'=>'T-Shirt',           'icon'=>'tshirt',   'category'=>'ملابس_عامة','base_price'=>4,  'description'=>'express:6', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'شورت',           'name_en'=>'Shorts',            'icon'=>'shorts',   'category'=>'ملابس_عامة','base_price'=>4,  'description'=>'express:6', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'فانيلة رياضية',  'name_en'=>'Sports Vest',       'icon'=>'sports',   'category'=>'ملابس_عامة','base_price'=>4,  'description'=>'express:6', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'فنيلة داخلية',   'name_en'=>'Undershirt',        'icon'=>'inner',    'category'=>'ملابس_عامة','base_price'=>2,  'description'=>'express:3', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'سروال قصير',     'name_en'=>'Short Trousers',    'icon'=>'shorts',   'category'=>'ملابس_عامة','base_price'=>2,  'description'=>'express:3', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'سروال طويل',     'name_en'=>'Long Trousers',     'icon'=>'pants',    'category'=>'ملابس_عامة','base_price'=>2,  'description'=>'express:3', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'جوارب',          'name_en'=>'Socks',             'icon'=>'socks',    'category'=>'ملابس_عامة','base_price'=>1,  'description'=>'express:2', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'معطف / بالطوية','name_en'=>'Coat / Overcoat',   'icon'=>'coat',     'category'=>'ملابس_عامة','base_price'=>5,  'description'=>'express:8', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'مريول',          'name_en'=>'Apron',             'icon'=>'apron',    'category'=>'ملابس_عامة','base_price'=>5,  'description'=>'express:8', 'created_at'=>now(),'updated_at'=>now()],
            // بدل وزي
            ['name_ar'=>'بدلة عسكرية',   'name_en'=>'Military Suit',     'icon'=>'suit',     'category'=>'بدل_وزي','base_price'=>8,  'description'=>'express:12','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'بدلة صوف',      'name_en'=>'Wool Suit',         'icon'=>'suit',     'category'=>'بدل_وزي','base_price'=>10, 'description'=>'express:20','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'بدلة سفاري',    'name_en'=>'Safari Suit',       'icon'=>'suit',     'category'=>'بدل_وزي','base_price'=>6,  'description'=>'express:12','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'بدلة رياضية',   'name_en'=>'Sports Suit',       'icon'=>'suit',     'category'=>'بدل_وزي','base_price'=>5,  'description'=>'express:10','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'بدلة باكستانية','name_en'=>'Pakistani Suit',    'icon'=>'suit',     'category'=>'بدل_وزي','base_price'=>4,  'description'=>'express:6', 'created_at'=>now(),'updated_at'=>now()],
            // ثياب خارجية
            ['name_ar'=>'جاكيت',          'name_en'=>'Jacket',            'icon'=>'jacket',   'category'=>'ثياب_خارجية','base_price'=>8,  'description'=>'express:15','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'بلوزة حرير',     'name_en'=>'Silk Blouse',       'icon'=>'blouse',   'category'=>'ثياب_خارجية','base_price'=>5,  'description'=>'express:8', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'فستان',          'name_en'=>'Dress',             'icon'=>'dress',    'category'=>'ثياب_خارجية','base_price'=>7,  'description'=>'express:40','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'تنورة',          'name_en'=>'Skirt',             'icon'=>'dress',    'category'=>'ثياب_خارجية','base_price'=>5,  'description'=>null,        'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'بشت / فرو',     'name_en'=>'Bisht / Fur',       'icon'=>'coat',     'category'=>'ثياب_خارجية','base_price'=>20, 'description'=>'express:40','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'قميص حرير',      'name_en'=>'Silk Shirt',        'icon'=>'shirt',    'category'=>'ثياب_خارجية','base_price'=>5,  'description'=>'express:8', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'عباية',          'name_en'=>'Abaya',             'icon'=>'abaya',    'category'=>'ثياب_خارجية','base_price'=>10, 'description'=>'express:20','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'طرحة / نقاب',   'name_en'=>'Hijab / Niqab',    'icon'=>'hijab',    'category'=>'ثياب_خارجية','base_price'=>3,  'description'=>'express:6', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'قميص نوم',       'name_en'=>'Nightgown',         'icon'=>'nightgown','category'=>'ثياب_خارجية','base_price'=>5,  'description'=>'express:8', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'ستار',           'name_en'=>'Curtain',           'icon'=>'curtain',  'category'=>'ثياب_خارجية','base_price'=>15, 'description'=>'express:30','created_at'=>now(),'updated_at'=>now()],
            // مفروشات
            ['name_ar'=>'شرشف مزدوج',    'name_en'=>'Double Bed Sheet',  'icon'=>'sheet',    'category'=>'مفروشات','base_price'=>10, 'description'=>'express:15','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'شرشف مفرد',     'name_en'=>'Single Bed Sheet',  'icon'=>'sheet',    'category'=>'مفروشات','base_price'=>7,  'description'=>'express:10','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'كيس مخدة',      'name_en'=>'Pillow Case',       'icon'=>'pillow',   'category'=>'مفروشات','base_price'=>1,  'description'=>'express:4', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'روب حمام',       'name_en'=>'Bath Robe',         'icon'=>'robe',     'category'=>'مفروشات','base_price'=>8,  'description'=>'express:15','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'بيجامة',         'name_en'=>'Pajama',            'icon'=>'pajama',   'category'=>'مفروشات','base_price'=>8,  'description'=>'express:12','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'فانيلية صوف',    'name_en'=>'Wool Vest',         'icon'=>'wool',     'category'=>'مفروشات','base_price'=>5,  'description'=>'express:8', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'منشفة / منديل', 'name_en'=>'Towel',             'icon'=>'towel',    'category'=>'مفروشات','base_price'=>5,  'description'=>'express:8', 'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'مفرش',           'name_en'=>'Mattress Cover',    'icon'=>'mattress', 'category'=>'مفروشات','base_price'=>25, 'description'=>'express:45','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'سجاد / موكيت',  'name_en'=>'Carpet / Rug',     'icon'=>'carpet',   'category'=>'مفروشات','base_price'=>10, 'description'=>'express:25','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'بطانية',         'name_en'=>'Blanket',           'icon'=>'blanket',  'category'=>'مفروشات','base_price'=>10, 'description'=>'express:25','created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'شوار',           'name_en'=>'Duvet',             'icon'=>'duvet',    'category'=>'مفروشات','base_price'=>25, 'description'=>null,        'created_at'=>now(),'updated_at'=>now()],
            ['name_ar'=>'إبداء',          'name_en'=>'Mattress',          'icon'=>'mattress', 'category'=>'مفروشات','base_price'=>50, 'description'=>null,        'created_at'=>now(),'updated_at'=>now()],
        ]);
        // Default admin account. Credentials come from .env (ADMIN_PHONE / ADMIN_PASSWORD) —
        // never hardcoded — so every deployment gets its own admin password instead of a
        // shared default. Change ADMIN_PASSWORD in .env before re-seeding on a live server.
        $adminPhone    = env('ADMIN_PHONE', '0500000000');
        $adminPassword = env('ADMIN_PASSWORD');
        if (!$adminPassword) {
            if (app()->environment('production')) {
                throw new \RuntimeException('ADMIN_PASSWORD must be set in .env before seeding in production.');
            }
            $adminPassword = 'ChangeMe123!'; // local/dev fallback only
        }
        if (!User::where('phone', $adminPhone)->exists()) {
            $admin = User::create(['name' => 'مدير لماء', 'phone' => $adminPhone, 'password' => Hash::make($adminPassword)]);
            $admin->forceFill(['role' => 'admin', 'total_orders' => 0])->save();
            echo "✅ Admin created — phone: {$adminPhone} (password set from ADMIN_PASSWORD in .env)\n";
        }
    }
}
