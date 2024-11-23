<!DOCTYPE html>
<html>
<head>
    <style>
        table, td, th {
            border: 1px solid black;
            text-align: center;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<div dir="rtl">
    <h5>{{__('table_name')}} : {{__("messages.".$data['tableName'])}}</h5>
    <h5>{{__('print_date')}} : {{\Carbon\Carbon::now()->toFormattedDateString()}}</h5>
</div>
<table dir="rtl">
    <tr>
        @foreach($data['head'] as $key => $value)
            {{--            @if(is_array($value) && isset($value['relation']))--}}
            {{--                <th class="text-center">{{__($value['relation']['value'])}}</th>--}}
            {{--            @else--}}

            {{--            @endif--}}
            @php
                if (is_string($value)){
                    $value = explode('|' , $value);
                    $tempType = $value[0];
                    unset($value[0]);
                    $validation = isset($value[1]) ? implode(" ",$value) : '';
                    $value = $tempType;
                }else{
                    $validation = '';
                }
            @endphp
            @if($value !== "file" && $value !== "image")
                <th class="text-center">{{__("messages.".$key)}}</th>
            @endif
        @endforeach
    </tr>
    @foreach($data['body'] as $item)
        <tr>
            @foreach($data['head'] as $key => $value)
                @if(is_array($value) && isset($value['relation']))
                    @php
                        $objVal = isset($item->{$value['relation']['relationFunc']}) ?
                        checkObjectInstanceofTranslation($item->{$value['relation']['relationFunc']},$value['relation']['value']) : $item->{$key};
                    @endphp
                    <td class="text-center">{{ $objVal ?? "-" }}</td>
                @else
                    @php
                        if (is_string($value)){
                            $value = explode('|' , $value);
                            $tempType = $value[0];
                            unset($value[0]);
                            $validation = isset($value[1]) ? implode(" ",$value) : '';
                            $value = $tempType;
                        }else{
                            $validation = '';
                        }
                    @endphp
                    @if($value !== "file" && $value !== "image")
                        <td class="text-center">{{ checkObjectInstanceofTranslation($item,$key) ?? "-" }}</td>
                    @endif
                @endif
            @endforeach
        </tr>
    @endforeach
</table>

</body>
</html>
