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

     'accepted' => 'ال :attribute يجب أن يكون مقبول.',
     'active_url' => 'الـ :attribute ليس رابط صحيح.',
     'after' => 'ال :attribute يجب أن يكون من صيغة التاريخ بعد :date.',
     'after_or_equal' => 'ال :attribute يجب أن يكون من صيغة تاريخ بعد أو يساوي :date.',
     'alpha' => 'ال :attribute يمكن أن يحوي فقط محارف',
     'alpha_dash' => 'The :attribute يمكن أن يحوي فقط محارف، أرقام ، dashes و underscores.',
     'alpha_num' => 'ال :attribute يمكن أن يحوي فقط محارف، أرقام .',
     'array' => 'ال :attribute يجب أن يكون مصفوفة.',
     'before' => 'ال :attribute يجب أن يكون تاريخ قبل :date.',
     'before_or_equal' => 'ال :attribute يجب أن يكون تاريخ قبل أو يساوي :date.',
     'between' => [
         'numeric' => 'ال :attribute يجب أن يكون بين :min و :max.',
         'file' => 'ال :attribute يجب أن يكون بين :min و :max كيلوبايت.',
         'string' => 'ال :attribute يجب أن يكون بين :min و :max محرف.',
         'array' => 'ال :attribute يجب أن يكون بين  :min و :max عنصر.',
     ],
     'boolean' => 'ال :attribute الحقل يجب أن يكون صح أو خطأ.',
     'confirmed' => 'ال :attribute التأكيد غير صحيح.',
     'date' => 'ال :attribute ليس تاريخ صحيح.',
     'date_format' => 'ال :attribute غير متوافق بالصيغة مع :format.',
     'different' => 'ال :attribute و :other يجب أن يكونو مختلفين.',
     'digits' => 'ال :attribute يجب أن يكون :digits digits.',
     'digits_between' => 'ال :attribute يجب أن يكون بين :min و :max digits.',
     'dimensions' => 'ال :attribute أبعاد الصورة غير صحيحة.',
     'distinct' => 'ال :attribute يملك قيمة مكررة.',
     'email' => 'ال :attribute يجب أن يكون قيمة بريد الكتروني صحيح.',
     'exists' => 'ال المحدد :attribute غير صحيح.',
     'file' => 'ال :attribute يجب أن يكون ملف.',
     'filled' => 'ال :attribute الحقل يجب أن يحتوي قيمة.',
     'gt' => [
         'numeric' => 'ال :attribute يجب أن يكون أكبر من :value.',
         'file' => 'ال :attribute يجب أن يكون أكبر من :value كيلوبايت.',
         'string' => 'ال :attribute يجب أن يكون أكبر من :value محرف.',
         'array' => 'ال :attribute يجب أن يحتوي على أكثر من :value عنصر.',
     ],
     'gte' => [
         'numeric' => 'ال :attribute  يجب أن يكون أكبر من أو يساوي :value.',
         'file' => 'ال :attribute  يجب أن يكون أكبر من أو يساوي :value كيلوبايت.',
         'string' => 'ال :attribute  يجب أن يكون أكبر من أو يساوي :value محرف.',
         'array' => 'ال :attribute يجب ان يحتوي :value عنصر أو أكثر.',
     ],
     'image' => 'ال :attribute يجب أن يكون صورة.',
     'in' => 'ال المحدد :attribute غير صحيح.',
     'in_array' => 'ال :attribute الحقل غير موجود ضمن :other.',
     'integer' => 'ال :attribute يجب أن يكون عدد حقيقي.',
     'ip' => 'ال :attribute يجب أن يكون عنوان IP.',
     'ipv4' => 'ال :attribute يجب أن يكون عنوان صحيح من النمط IPv4.',
     'ipv6' => 'ال :attribute يجب أن يكون عنوان صحيح من النمط IPv6.',
     'json' => 'ال :attribute يجب أن يكون JSON string صحيح.',
     'lt' => [
         'numeric' => 'ال :attribute  يجب أن يكون أقل من:value.',
         'file' => 'ال :attribute  يجب أن يكون أقل من:value كيلوبايت.',
         'string' => 'ال :attribute  يجب أن يكون أقل من:value محرف.',
         'array' => 'ال :attribute يجب أن يحتوي أقل من :value عنصر.',
     ],
     'lte' => [
         'numeric' => 'ال :attribute يجب أن يكون أقل من أو يساوي :value.',
         'file' => 'ال :attribute يجب أن يكون أقل من أو يساوي :value كيلوبايت.',
         'string' => 'ال :attribute يجب أن يكون أقل من أو يساوي :value محرف.',
         'array' => 'ال :attribute يجب أن لا يحتوي على أكثر من :value عنصر.',
     ],
     'max' => [
         'numeric' => 'ال :attribute يفضل أن لا يكون أكبر من  :max.',
         'file' => 'ال :attribute يفضل أن لا يكون أكبر من  :max كيلوبايت.',
         'string' => 'ال :attribute يفضل أن لا يكون أكبر من  :max محرف.',
         'array' => 'ال :attribute يفضل أن لا يحوي أكثر من  :max عنصر.',
     ],
     'mimes' => 'ال :attribute يجب أن يحتوي ملف من النوع: :values.',
     'mimetypes' => 'ال :attribute يجب أن يحتوي ملف من النوع: :values.',
     'min' => [
         'numeric' => 'ال :attribute يجب أن يكون على الأقل :min.',
         'file' => 'ال :attribute يجب أن يكون على الأقل :min كيلوبايت.',
         'string' => 'ال :attribute يجب أن يكون على الأقل :min محرف.',
         'array' => 'ال :attribute يجب أن يحتوي على الأقل :min عنصر.',
     ],
     'not_in' => 'ال المحدد :attribute  غير صحيح.',
     'not_regex' => 'ال :attribute الصيغة غير صحيحة.',
     'numeric' => 'ال :attribute يجب أن يكون رقم.',
     'present' => 'ال :attribute الحقل يجب أن يكون حاضر.',
     'regex' => 'ال :attribute الصيغة غير صحيحة.',
     'required' => 'ال :attribute حقل مطلوب.',
     'required_if' => 'ال :attribute حقل مطلوب عندما :other لها :value.',
     'required_unless' => 'ال :attribute الحقل مطلوب طالما :other ضمن القيم :values.',
     'required_with' => 'ال :attribute الحقل مطلوب عندما :values is present.',
     'required_with_all' => 'ال :attribute الحقل مطلوب عندما :values are present.',
     'required_without' => 'ال :attribute الحقل مطلوب عندما :values is not present.',
     'required_without_all' => 'ال :attribute الحقل مطلوب عندما none of :values are present.',
     'same' => 'ال :attribute و :other يجب أن يتطابقو.',
     'size' => [
         'numeric' => 'ال :attribute يجب أن يكون :size.',
         'file' => 'ال :attribute يجب أن يكون :size كيلوبايت.',
         'string' => 'ال :attribute يجب أن يكون :size محرف.',
         'array' => 'ال :attribute يجب أن يحتوي :size عنصر.',
     ],
     'string' => 'ال :attribute يجب أن يكون سلسلة محرفية.',
     'timezone' => 'ال :attribute يجب أن يكون ضمن مجال ومني صحيح.',
     'unique' => 'ال :attribute موجود مسبقاً.',
     'uploaded' => 'ال :attribute فشل عملية التحميل.',
     'url' => 'ال :attribute صيغة غير صحيحة.',
     'uuid' => 'ال :attribute يجب أن يكون قيمة UUID مقبولة.',

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
             'rule-name' => 'custom-message',
         ],
     ],

     /*
     |--------------------------------------------------------------------------
     | Custom Validation Attributes
     |--------------------------------------------------------------------------
     |
     | The following language lines are used to swap our attribute placeholder
     | with something more reader friendly such as "E-Mail Address" instead
     | of "email". This simply helps us make our message more expressive.
     |
     */

     'attributes' => [],

];
