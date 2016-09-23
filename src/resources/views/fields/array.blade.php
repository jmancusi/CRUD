<!-- array input -->

@php
    $items = old($field['name']) ? (old($field['name'])) : (isset($field['value']) ? ($field['value']) : (isset($field['default']) ? ($field['default']) : '' ));
    if( empty($items) ){
        $items = '[]';
    }
@endphp
<div ng-app="arrayApp" ng-controller="arrayController" @include('crud::inc.field_wrapper_attributes') >

    <input class="array-json" type="hidden" id="{{ $field['name'] }}" name="{{ $field['name'] }}">

    <div class="array-container form-group">

        <div class="array-controls btn-group">
            <button class="btn btn-primary" type="button" ng-click="addItem()">Add {{$field['label']}}</button>
        </div>

        <table style="margin-top: 10px;" class="table" ng-init="field = '#{{ $field['name'] }}'; items = {{$items}}; max = {{isset($field['max']) ? $field['max'] : -1}}; min = {{isset($field['min']) ? $field['min'] : -1}};">

            <thead>
                <tr>
                    <th class="text-center"><i class="fa fa-sort"></i></th>
                    @foreach( $field['properties'] as $prop )
                    <th>
                        {{ $prop }}
                    </th>
                    @endforeach
                    <th class="text-center"><i class="fa fa-trash"></i></th>
                </tr>
            </thead>

            <tbody ui-sortable="sortableOptions" ng-model="items" class="table-striped">

                <tr ng-repeat="item in items" class="array-row">
                    <td>
                        <span class="btn btn-warning sort-handle"><span class="sr-only">sort item</span><i class="fa fa-sort" role="presentation" aria-hidden="true"></i></span>
                    </td>
                    @foreach( $field['properties'] as $prop => $label)
                    <td>
                        <input class="form-control" type="text" ng-model="item.{{ $prop }}">
                    </td>
                    @endforeach
                    <td>
                        <button ng-hide="min > -1 && $index < min" class="btn btn-danger" type="button" ng-click="removeItem(item);"><span class="sr-only">delete item</span><i class="fa fa-trash" role="presentation" aria-hidden="true"></i></button>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    {{-- @push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
        <style media="screen">

        </style>
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        {{-- YOUR JS HERE --}}
        <script type="text/javascript" src="https://cdn.jsdelivr.net/g/jquery.ui@1.11.4,angularjs@1.5.5,angular.ui-sortable@0.14.3"></script>
        <script>
            angular.module('arrayApp', ['ui.sortable'], function($interpolateProvider){
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            })
            .controller('arrayController', function($scope){

                $scope.sortableOptions = {
                    handle: '.sort-handle',
                    stop: function(){
                        console.log('khghjg');
                    }
                };

                $scope.addItem = function(){

                    if( $scope.max > -1 ){
                        if( $scope.items.length < $scope.max ){
                            var item = {};
                            $scope.items.push(item);
                        }
                    }
                    else {
                        var item = {};
                        $scope.items.push(item);
                    }
                }

                $scope.removeItem = function(item){
                    var index = $scope.items.indexOf(item);
                    $scope.items.splice(index, 1);
                }

                $scope.$watch('items', function(a, b){

                    if( $scope.min > -1 ){
                        while($scope.items.length < $scope.min){
                            $scope.addItem();
                        }
                    }

                    if( typeof $scope.items != 'undefined' && $scope.items.length ){

                        if( typeof $scope.field != 'undefined'){
                            if( typeof $scope.field == 'string' ){
                                $scope.field = $($scope.field);
                            }
                            $scope.field.val( angular.toJson($scope.items) );
                        }
                    }
                }, true);

                if( $scope.min > -1 ){
                    for(var i = 0; i < $scope.min; i++){
                        $scope.addItem();
                    }
                }
            });
        </script>

    @endpush
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
