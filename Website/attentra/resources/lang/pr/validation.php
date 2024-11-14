<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ' :attribute باید پذیرفته شود',
    'active_url'           => ' :attribute آدرس صحیحی ندارد',
    'after'                => ' :attribute باید بعد از  :date باشد',
    'alpha'                => ' :attribute فقط می تواند شامل حروف باشد',
    'alpha_dash'           => ' :attribute فقط می تواند شامل حروف اعداد و علامت ها باشد',
    'alpha_num'            => ' :attribute فقط می تواند شامل حروف اعداد باشد',
    'array'                => ' :attribute باید یک آرایه باشد',
    'before'               => ' :attribute باید قبل از :date باشد',
    'between'              => [
        'numeric' => ' :attribute باید بین :min و :max باشد',
        'file'    => ' :attribute باید بین :min و :max کیلو بایت باشد',
        'string'  => ' :attribute باید بین :min و :max حرف باشد',
        'array'   => ' :attribute باید بین :min و :max مقدار باشد',
    ],
    'boolean'              => ' :attribute مو تواند مقدار درست یا نادرست باشد',
    'confirmed'            => ':attribute مطابقت ندارد',
    'date'                 => ':attribute مقدار صحیحی برای تاریخ ندارد',
    'date_format'          => ':attribute فرمت صحیحی ندارد :format.',
    'different'            => ':attribute و :other باید با هم تفاوت داشته باشد',
    'digits'               => ':attribute must be :digits digits.',
    'digits_between'       => ':attribute باید بین :min و :max عدد باشد.',
    'dimensions'           => ':attribute ابعاد تصویری درستی ندارد',
    'distinct'             => ':attribute یک مقدار تکراریست',
    'email'                => ':attribute مقدار ایمیل نادرستی دارد',
    'exists'               => ' :attribute انتخابی صحیح نیست',
    'file'                 => ':attribute باید یک مقدار فایل باشد',
    'filled'               => ':attribute باید حتما لحاظ شود',
    'image'                => ':attribute باید حتما تصویر باشد',
    'in'                   => ' :attribute انتخابی مقدار درستی نیست',
    'in_array'             => ':attribute وجود ندارد در :other.',
    'integer'              => ':attribute حتما باید عدد باشد',
    'ip'                   => ':attribute آدرس آی پی صحیحی ندارد',
    'json'                 => ':attribute مقدار صحیحی از رشته جیسون ندارد',
    'max'                  => [
        'numeric' => ':attribute نباید بزرگتر از این مقدار باشد: :max.',
        'file'    => ':attribute نباید بزرگتر از این مقدار باشد: :max کیلو بایت.',
        'string'  => ':attribute نباید بزرگتر از این مقدار باشد: :max حرف.',
        'array'   => ':attribute نباید بزرگتر از این مقدار باشد: :max مقدار.',
    ],
    'mimes'                => ':attribute must be a file of type: :values.',
    'mimetypes'            => ':attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => ':attribute :نباید کوچکتر از این مقدار باشد :min.',
        'file'    => ':attribute :نباید کوچکتر از این مقدار باشد :min کیلو بایت.',
        'string'  => ':attribute :نباید کوچکتر از این مقدار باشد :min حرف.',
        'array'   => ':attribute :نباید کوچکتر از این مقدار باشد :min مقدار.',
    ],
    'not_in'               => 'selected :attribute is invalid.',
    'numeric'              => ':attribute حتما باید عدد باشد',
    'present'              => ':attribute باید حتما در دسترس باشد',
    'regex'                => ':attribute باید حتما با حروف لاتین باشد.',
    'required'             => ':attribute باید حتما وارد شود',
    'required_if'          => ':attribute field is required when :other is :value.',
    'required_unless'      => ':attribute field is required unless :other is in :values.',
    'required_with'        => ':attribute field is required when :values is present.',
    'required_with_all'    => ':attribute field is required when :values is present.',
    'required_without'     => ':attribute field is required when :values is not present.',
    'required_without_all' => ':attribute field is required when none of :values are present.',
    'same'                 => ':attribute and :other must match.',
    'size'                 => [
        'numeric' => ':attribute باید حتما این اندازه باشد :size.',
        'file'    => ':attribute حتما باید :size کیلو بایت باشد.',
        'string'  => ':attribute حتما باید  :size حرف داشته باشد.',
        'array'   => ':attribute حتما باید شامل :size عدد باشد.',
    ],
    'string'               => ':attribute باید حتما رشته باشد',
    'timezone'             => ':attribute must be a valid zone.',
    'unique'               => ' :attribute قبلا انتخاب شده',
    'uploaded'             => ':attribute آپلود نشد',
    'url'                  => ':attribute فرمت اشتباهی دارد',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'missionperson' => 'الکی',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'title' => 'عنوان',
        'missionperson' => 'حداقل یک کاربر',
        "name" => "نام",
        "user_name" => "نام کاربری",
        "email" => "پست الکترونیکی",
        "first_name" => "نام",
        "last_name" => "نام خانوادگی",
        "password" => "رمز عبور",
        "password_confirmation" => "تاییدیه ی رمز عبور",
        "city" => "شهر",
        "country" => "کشور",
        "address" => "نشانی",
        "phone" => "تلفن",
        "mobile" => "تلفن همراه",
        "age" => "سن",
        "sex" => "جنسیت",
        "gender" => "جنسیت",
        "day" => "روز",
        "month" => "ماه",
        "year" => "سال",
        "hour" => "ساعت",
        "minute" => "دقیقه",
        "second" => "ثانیه",
        "text" => "متن",
        "content" => "محتوا",
        "description" => "توضیحات",
        "excerpt" => "گلچین کردن",
        "date" => "تاریخ",
        "time" => "زمان",
        "available" => "موجود",
        "size" => "اندازه",
        "family"=>"نام خانوادگی",
        "code"=>"شماره کارمندی",
    ],

];
