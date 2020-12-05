<?php

namespace App\Nova;

use App\Enum\AccountStatus;
use App\Enum\AccountType;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use NovaAttachMany\AttachMany;

class Organization extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Organization';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Name')->rules('required')->sortable(),
            Text::make('Address Line 1', 'address_1')->rules('required'),
            Text::make('Address Line 2', 'address_2')->nullable(),
            Text::make('City')->rules('required'),
            Select::make('State')
                ->options(function () {
                    $stateData = json_decode(file_get_contents(resource_path('data/states.json')));
                    return collect($stateData)->all();
                })
                ->rules('required'),
            Text::make('Zip')->rules('required'),
            Select::make('Country')
                ->options(function () {
                    $countries = json_decode(file_get_contents(resource_path('data/countries.json')));
                    return collect($countries)->all();
                })
                ->rules('required')
                ->withMeta(['value' => 'US']),
            Text::make('Phone')->rules('required'),
            Text::make('Website'),
            BelongsToMany::make('Disciplines')->hideFromDetail(),
            AttachMany::make('Disciplines')->rules('min:1')->hideFromDetail(),
            Text::make('Primary Contact Name')->rules('required')->hideFromIndex(),
            Text::make('Primary Contact Email')->rules('required')->hideFromIndex(),
            Text::make('Primary Contact Phone')->rules('required')->hideFromIndex(),
            BelongsTo::make('Employee Count', 'employeeCount')->rules('required')->viewable(false),
            Select::make('Account Status')
                ->options(function() {
                    return AccountStatus::toLabelArray();
                })
                ->rules('required'),
            Select::make('Account Type')
                ->options(function() {
                    return AccountType::toLabelArray();
                })
                ->rules('required'),
            HasMany::make('Projects')->onlyOnDetail(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
