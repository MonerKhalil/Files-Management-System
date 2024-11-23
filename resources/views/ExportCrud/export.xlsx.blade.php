<!DOCTYPE HTML>
<html lang="{{app()->getLocale()}}">
<head>
    <title>{{ $title ?? "Table Xlsx"}}</title>
</head>
<body>
<div class="d-flex justify-content-center profile-container">
    <div class='col-md-10 text-center sort-profile' id='sort-profile'>
        <div class='row'>
            <div class='col-md-12 text-center' >
                <table class='table table-light table-striped table-bordered' id='excel-table' style="background-color: transparent; border:2px solid black; margin-top:15px;">
                    <tr>
                        @foreach($data['head'] as $key => $value)
                            {{--                            @if(is_array($value) && isset($value['relation']))--}}
                            {{--                                <th class="text-center">{{__($value['relation']['value'])}}</th>--}}
                            {{--                            @else--}}
                            {{--                                --}}
                            {{--                            @endif--}}
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
                                <th class="text-center">{{$data["isEmpty"] ? $key : __("messages.".$key)}}</th>
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
            </div>
        </div>
    </div>
</div>
</body>
</html>
